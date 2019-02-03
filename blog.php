<?php

function add_post($title,$contents,$category){
    $title      = mysqli_real_escape_string($title);
    $contents   = mysqli_real_escape_string($contents);
    $category   = (int)$category;
    
    mysqli_query("INSERT INTO `posts` SET
                `cat_id`     = {$category},
                `title`      = '{$title}',
                `contents`   = '{$contents}',
                `date_posted`= NOW()");
}

function edit_post($id,$title,$contents,$category){
    $id         = (int)$id;
    $title      = mysqli_real_escape_string($title);
    $contents   = mysqli_real_escape_string($contents);
    $category   = (int)$category;
    
    mysqli_query("UPDATE `posts` SET
                `cat_id`     = {$category},
                `title`      = '{$title}',
                `contents`   = '{$contents}'
                WHERE `id` = {$id}");  
}

function add_category($name){
  $name = mysqli_real_escape_string($name);
  
  mysqli_query("INSERT INTO `categories` SET `name` = '{$name}'");
}

function delete($table, $id){
    $table = mysqli_real_escape_string($table);
    $id    = (int)$id;
    
    mysqli_query("DELETE FROM `{$table}` WHERE `id`= {$id} ");
    
}

function get_posts($id = null, $cat_id = null){
    $posts = array();
    $query = "SELECT
              `posts`.`id` AS `post_id` ,
               `categories`.`id` AS `category_id`,
               `title`,`contents`,`date_posted`,
               `categories`.`name`
               FROM `posts`
               INNER JOIN `categories` ON `categories`.`id` = `posts`.`cat_id` " ;
    if(isset($id)){
        $id = (int)$id;
        $query .= " WHERE `posts`.`id` = {$id} ";
             }
    if(isset($cat_id)){
        $cat_id = (int)$cat_id;
        $query .= " WHERE `cat_id` = {$cat_id} ";
             }         
        
    $query .= "ORDER BY `post_id` DESC";
    
    $query = mysqli_query($query);
    
    while($row = mysql_fetch_assoc($query)){
    $posts[] = $row;
   }
   return $posts;
}

function get_categories($id = null){
   $categories = array();
   
   $query = mysqli_query("SELECT `id`,`name` FROM `categories`");
   
   while($row = mysqli_fetch_assoc($query)){
    $categories[] = $row;
   }
   
   return $categories;
}

function category_exists($field,$name){
    $name = mysqli_real_escape_string($name);
    $field = mysqli_real_escape_string($field);
    
    $query = mysqli_query("SELECT COUNT(1) FROM categories WHERE `{$field}` = '{$name}'");
    
    return(mysqli_result($query,0) == 0)?false : true;
}