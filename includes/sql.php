<?php
  require_once('includes/load.php');


function findById($id)
{
  global $db;
  $id = (int)$id;
  $sql = $db->query("SELECT * FROM products WHERE id='{$db->escape($id)}' LIMIT 1");
  if($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;

}

function deleteById($id)
{
    global $db;

    $sql = "DELETE FROM products WHERE id= " . $db->escape($id) . " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
}

function filter($search,$category,$statut){
    global $db;
    $sql = "SELECT * FROM products WHERE categorie IN ($category) AND statut IN ($statut) AND (title LIKE '%$search%' OR artist LIKE '%$search%')";
    $results = $db->query($sql);
    $results = $db->resultsInArray($results);
    return json_encode(['cd' => $results]);

}

function addProducts($data){
    global $db;
    $title     = removeHTMLChars($db->escape($data['title']));
    $artist    = removeHTMLChars($db->escape($data['artist']));
    $category  = removeHTMLChars($db->escape($data['category']));
    $cover     = removeHTMLChars($db->escape($data['coverURL']));
    $quantity  = removeHTMLChars($db->escape($data['quantity']));
    $price     = removeHTMLChars($db->escape($data['price']));
    $statut    = removeHTMLChars($db->escape($data['statut']));
    $date      = makeDate();


    $query  = "INSERT INTO products (title,artist,quantity,price,categorie,cover,statut,date) VALUES ('{$title}', '{$artist}', '{$quantity}', '{$price}', '{$category}', '{$cover}', '{$statut}', '{$date}') ON DUPLICATE KEY UPDATE title='{$title}'";
    if($db->query($query)){
        return true;
    } else {
        return false;
    }
}

function updateProducts($data){
    global $db;
    $id        = removeHTMLChars($db->escape($data['id']));
    $title     = removeHTMLChars($db->escape($data['title']));
    $artist    = removeHTMLChars($db->escape($data['artist']));
    $category  = removeHTMLChars($db->escape($data['category']));
    $cover     = removeHTMLChars($db->escape($data['coverURL']));
    $quantity  = removeHTMLChars($db->escape($data['quantity']));
    $price     = removeHTMLChars($db->escape($data['price']));
    $statut    = removeHTMLChars($db->escape($data['statut']));
    $date      = makeDate();


    $query  = "UPDATE products SET title = '{$title}',artist = '{$artist}',quantity = '{$quantity}',price = '{$price}',categorie = '{$category}',cover = '{$cover}',statut = '{$statut}',date = '{$date}' WHERE id = '{$id}'";
    if($db->query($query)){
        return true;
    } else {
        return false;
    }
}


function productsAll(){
    global $db;
    $sql  =" SELECT * FROM products ORDER BY id ASC";
    $result = $db->query($sql);
    return $db->resultsInArray($result);

}

?>
