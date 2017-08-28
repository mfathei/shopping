<?php
session_start();
require_once "utils.php";

$database = new Database();
$db = null;
$cart = null;
if (isset($_SESSION['cart']))
{
    try {

            $db = $database->getMySQLConnection('shopping');
            // save order
            $query = "INSERT INTO `shopping`.`order` (
                `name`,
                `status`,
                `username`
            ) 
            VALUES
                (
                    'New Order',
                    0,
                    'acc2'
                ) ;";
            // local
            $stmt = $db->prepare($query);
//            $stmt->bindParam(":name", $name);
            $stmt->execute();

            // save order items
            $cart = unserialize(serialize($_SESSION['cart']));

        $query = "INSERT INTO `shopping`.`order_items` (
            `order_id`,
            `product_id`,
            `quantity`,
            `price`
        ) 
        VALUES
            (
                @newoid,
                :product_id,
                :quantity,
                :price
            ) ;";

        for ($i = 0; $i < count($cart); $i++)
        {

            $stmt = $db->prepare($query);
            $stmt->bindParam(":product_id", $cart[$i]->id);
            $stmt->bindParam(":quantity", $cart[$i]->quantity);
            $stmt->bindParam(":price", $cart[$i]->price);
            $stmt->execute();
        }

        // clear list after saving it
        unset($_SESSION['cart']);

    }catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
        // WriteFuncParams("getTableData", $ex->getMessage());
    }catch (Exception $ex) {
        print "Error!: " . $ex->getMessage() . "<br/>";
        die();
    }

}

?>

Thanks for buying, Click <a href="index.php">here</a> to continue shopping.

