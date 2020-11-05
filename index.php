<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once '../data.php';
require_once 'simple_html_dom.php';

$rootUrl = 'http://some.example.com';
$links = [];
$boxLinks = [];

function parser($pdo, $url)
{
    fetchLinks($pdo);
    pushLinksDB(2, 5, $pdo, $url);
}

parser($pdo, $rootUrl);

function getContent($url)
{

    $username = 'example';
    $password = 'example';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function pushLinksDB($min, $max, $pdo, $url)
{
    $links = [];
    for ($i = $min; $i < $max; $i++) {
        $boxLinks[$i] = (getProductLinks($url, $i, $links));
        putLinksDB($boxLinks[$i], $pdo);
    }
}

function fetchLinks($pdo)
{

    $randomInt = mt_rand(30, 50);

    $fetchLinks = $pdo->query("SELECT * FROM `parser_link_product` WHERE point_parse=0 LIMIT $randomInt");

    foreach ($fetchLinks as $link) {
        $push = getPropsProduct($link['link']);
        putProducts($push, $pdo);

        $id = $link['id'];

        $updateLink = $pdo->prepare("UPDATE `parser_link_product` SET point_parse=:point WHERE id=$id");
        $updateLink->execute(array('point' => '1'));
    }
}

function getProductLinks($url, $page, $links)
{

    $file = getContent($url . '?page=' . $page);
    $html = new simple_html_dom();
    $html->load($file);

    foreach ($html->find(".item-prod h4 a") as $value) {
        $links [] = $value->href;
    }

    return $links;
}

function putLinksDB($links, $pdo)
{

    $stmt = "INSERT INTO `parser_link_product` (`link`, `point_parse`) VALUES (:link, :point)";
    $stmt = $pdo->prepare($stmt);
    foreach ($links as $link) {
        $stmt->execute(array('link' => $link, 'point' => 0));
    }
}

function getPropsProduct($url)
{

    $file = getContent($url);
    $html = new simple_html_dom();
    $html->load($file);

    $props = [];
    $moreProps = [];
    $additionalProps = [];

    $props ['title'] = trim($html->find(".product-header")[0]->plaintext);
    $props ['location'] = trim($html->find(".loc span")[0]->plaintext);
    $props ['term'] = trim($html->find(".offer span")[0]->plaintext);
    $props ['status'] = trim(explode(":", $html->find(".offer2")[0]->plaintext)[1]);
    $props ['price'] = trim($html->find("span.main-price")[0]->plaintext);
    $props ['description'] = trim($html->find("#tab-description span")[0]->next_sibling()->plaintext);


    foreach ($html->find(".table tbody tr") as $value) {

        $row = explode(':', $value->plaintext);
        $moreProps [trim($row[0])] = trim($row[1]);
    }

    $keys = array_keys($moreProps);

    $basicProps = array(
        'Тип недвижимости' => 'property_type',
        'Спальни' => 'bedrooms',
        'Расстояние до моря' => 'distance_sea',
        'Ванные' => 'bathrooms',
        'Крытая площадь' => 'covered_area',
        'Бассейн' => 'pool',
        'Мебель' => 'furniture',
        'Площадь участка' => 'land_area',
        'Год постройки' => 'since_year'
    );

    foreach ($basicProps as $key => $val) {
        if (in_array($key, $keys)) {
            $props [$val] = $moreProps[$key];
        }
    }

    foreach ($html->find(".add-opt span") as $element) {
        $additionalProps [] = $element->title;
    }

    $props['addition'] = $additionalProps;

    return $props;
}

function getAdditionalProps($pdo)
{

    $resProps = [];

    $stmt = $pdo->query("SELECT `service` FROM  `parser_additional_service`");

    foreach ($stmt as $row) {
        $resProps[] = $row['service'];
    }

    return $resProps;
}

function putProducts($arr, $pdo)
{

    $additionalProps = array_pop($arr);
    $getProps = getAdditionalProps($pdo);

    foreach ($additionalProps as $prop) {

        if (!(in_array($prop, $getProps))) {

            $stmt = $pdo->prepare("INSERT INTO `parser_additional_service` SET service=:prop");
            $stmt->execute(array('prop' => $prop));
        }
    }

    $arrItemsSql = [
        'title',
        'location',
        'term',
        'status',
        'price',
        'property_type',
        'bedrooms',
        'distance_sea',
        'bathrooms',
        'pool',
        'furniture',
        'land_area',
        'covered_area',
        'since_year',
        'description'
    ];

    foreach ($arrItemsSql as $value) {
        if (!array_key_exists($value, $arr)) {
            $arr[$value] = null;
        }
    }

    $sql = "INSERT INTO `parser_products` (`title`, `local_place`, `term`, `status`, `price`, `property_type`, `bedrooms`, `distance_sea`, `bathrooms`, `pool`, `furniture`, `land_area`, `covered_area`, `since_year`,`description`) 
                                   VALUES ( :title, :location, :term, :status, :price, :property_type, :bedrooms, :distance_sea, :bathrooms, :pool, :furniture, :land_area, :covered_area, :since_year,:description)";
    $stmtProducts = $pdo->prepare($sql);
    $pdo->exec("set names utf8");
    $stmtProducts->execute($arr);
    $id = $pdo->lastInsertId();

    $arrIds = fetchIdsAdditional($pdo, $additionalProps);
    putPropsChain($pdo, $id, $arrIds);
}

function fetchIdsAdditional($pdo, $arr)
{

    $string = "";
    $resultIDs = [];
    foreach ($arr as $item) {
        $string .= "'" . $item . "', ";
    }
    $string = substr($string, 0, -2);

    $selectIDs = $pdo->query("SELECT id FROM parser_additional_service WHERE service IN ($string)");
    foreach ($selectIDs as $id) {
        $resultIDs[] = $id['id'];
    }
    return $resultIDs;
}

function putPropsChain($pdo, $id, $arrIds)
{
    $sqlListService = "INSERT INTO `parser_props_chain` (`product_id`, `service_id`) VALUES (:product_id, :service_id)";
    $stmtList = $pdo->prepare($sqlListService);
    foreach ($arrIds as $serviceId) {
        $stmtList->execute(array('product_id' => $id, 'service_id' => $serviceId));
    }
}
