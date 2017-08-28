<?php
session_start();
require_once "utils.php";

$database = new Database();
$db = null;


if(isset($_GET['id']))
{
    try {
        $db = $database->getMySQLConnection('shopping');
        // print "Done!";
        $query = "select * from product where id = :id";// local
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $_GET['id']);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_OBJ);
//    echo json_encode($data);
//    echo print_r($data, true);

        $item = new Item();
        $item->id = $product->id;
        $item->name = $product->name;
        $item->desc = $product->desc;
        $item->price = $product->price;
        $item->quantity = 1;

        $index = -1;
        $sum = 0;
        if(isset($_SESSION['cart'])) {

            $cart = unserialize(serialize($_SESSION['cart']));
            for ($k = 0; $k < count($cart); $k++) {
                if ($_GET['id'] == $cart[$k]->id) {
                    $index = $k;
                    break;
                }
            }
        }

        if($index == -1)
        {
            $_SESSION['cart'][] = $item;
        }else
        {
            $cart[$index]->quantity++;
            $_SESSION['cart'] = $cart;
        }


        ?>

        <table cellpadding="2" cellspacing="2" border="1">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Sub Total</th>
            </tr>
            </thead>
            <tbody>


        <?php

        $cart = unserialize(serialize($_SESSION['cart']));
        for($i = 0; $i < count($cart); $i++)
        {
            $sum += $cart[$i]->quantity * $cart[$i]->price;
            ?>
            <tr>
                <td><?php echo $cart[$i]->id; ?></td>
                <td><?php echo $cart[$i]->name; ?></td>
                <td><?php echo $cart[$i]->price; ?></td>
                <td><?php echo $cart[$i]->quantity; ?></td>
                <td><?php echo $cart[$i]->quantity * $cart[$i]->price; ?></td>
            </tr>
        <?php
        }

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
<tr>
    <td colspan="4" align="right">Sum</td>
    <td align="left"><?php echo $sum; ?></td>
</tr>
            </tbody>
        </table>
<br>
<a href="index.php">Continue Shopping</a>

