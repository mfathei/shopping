<?php
session_start();
require_once "utils.php";

$database = new Database();
$db = null;

$index = -1;
$sum = 0;
$cart = null;

if(isset($_GET['id']) && isset($_GET['action']) && !isset($_POST['update'])) {
    try {

        if (isset($_SESSION['cart']))
            $cart = unserialize(serialize($_SESSION['cart']));

        if ($_GET['action'] == 'delete') {
            $index = idExist($_GET['id']);
            unset($cart[$index]);
            $cart = array_values($cart);
            $_SESSION['cart'] = $cart;
        } else {
            $db = $database->getMySQLConnection('shopping');
            // print "Done!";
            $query = "select * from product where id = :id";// local
            $stmt = $db->prepare($query);
            $stmt->bindParam(":id", $_GET['id']);
            $stmt->execute();

            $product = $stmt->fetch(PDO::FETCH_OBJ);
//    echo json_encode($data);
//    echo print_r($data, true);
if($product != null) {
    $item = new Item();
    $item->id = $product->id;
    $item->name = $product->name;
    $item->desc = $product->desc;
    $item->price = $product->price;
    $item->quantity = 1;

    if (isset($_SESSION['cart']))
        $index = idExist($_GET['id']);

    if ($index == -1) {
        $_SESSION['cart'][] = $item;
    } else {
        $cart[$index]->quantity++;
        $_SESSION['cart'] = $cart;
    }
}
        }

    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
        // WriteFuncParams("getTableData", $ex->getMessage());
    } catch (Exception $ex) {
        print "Error!: " . $ex->getMessage() . "<br/>";
        die();
    }

}


if(isset($_POST['update']))
{

    $arrQuantity = $_POST['quantity'];
    if (isset($_SESSION['cart'])) {
        $cart = unserialize(serialize($_SESSION['cart']));
        for ($j = 0; $j < count($cart); $j++) {
            $cart[$j]->quantity = $arrQuantity[$j];
            $_SESSION['cart'] = $cart;
        }
    }
}

    if(isset($_SESSION['cart'])) {
?>

  <form action="" method="post">
<table cellpadding="2" cellspacing="2" border="1">
    <thead>
    <tr>
        <td>Option</td>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity
            <input type="image" src="images/Save-icon.png">
            <input type="hidden" name="update">
        </th>
        <th>Sub Total</th>
    </tr>
    </thead>
    <tbody>


    <?php

        $cart = unserialize(serialize($_SESSION['cart']));
        for ($i = 0; $i < count($cart); $i++) {
            $sum += $cart[$i]->quantity * $cart[$i]->price;
            ?>
            <tr>
                <td><a href="cart.php?id=<?php echo $cart[$i]->id; ?>&action=delete"
                       onclick="return confirm('Are you sure ?');">
                        Delete</a></td>
                <td><?php echo $cart[$i]->id; ?></td>
                <td><?php echo $cart[$i]->name; ?></td>
                <td><?php echo $cart[$i]->price; ?></td>
                <td><input type="number" style="width : 50px;" name="quantity[]"
                           value="<?php echo $cart[$i]->quantity; ?>"></td>
                <td><?php echo $cart[$i]->quantity * $cart[$i]->price; ?></td>
            </tr>

            <?php
        }
        ?>
        <tr>
            <td colspan="5" align="right">Sum</td>
            <td align="left"><?php echo $sum; ?></td>
        </tr>
        </tbody>
        </table>
  </form>
        <?php
    }
        ?>
<br>
<a href="index.php">Continue Shopping</a> |  <a href="checkout.php">Checkout</a>

<?php

function idExist($id){
    $cart = unserialize(serialize($_SESSION['cart']));
    for ($k = 0; $k < count($cart); $k++) {
        if ($cart[$k]->id == $id) {
            return $k;
        }
    }

    return -1;
}

