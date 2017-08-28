<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 8/28/2017
 * Time: 5:28 PM
 */

session_start();

require_once "utils.php";

?>


<table border="1">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Buy</th>
    </tr>
    </thead>
    <tbody>

    <?php

    $database = new Database();
    $db = null;

    try {
        $db = $database->getMySQLConnection('shopping');
        // print "Done!";
        $query = "select * from product";// local
        $stmt = $db->prepare($query);
//    $stmt->bindParam(":tableName", $table);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
//    echo json_encode($data);
//    echo print_r($data, true);

        $count = count($data);

        for ($i = 0; $i < $count; $i++) {

            ?>
            <tr>
                <td><?php echo $data[$i]->id; ?></td>
                <td><?php echo $data[$i]->name; ?></td>
                <td><?php echo $data[$i]->desc; ?></td>
                <td><?php echo $data[$i]->price; ?></td>
                <td><?php echo $data[$i]->quantity; ?></td>
                <td><a href="cart.php?id=<?php echo $data[$i]->id; ?>&action=add" >
                        Order Now
                    </a></td>
            </tr>
            <?php
        }

    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
        // WriteFuncParams("getTableData", $ex->getMessage());
    } catch (Exception $ex) {
        print "Error!: " . $ex->getMessage() . "<br/>";
        die();
    }

    ?>


    </tbody>
</table>

