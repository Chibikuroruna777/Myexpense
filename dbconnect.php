<?php

    $post = $_POST;
    $date = $post["date"];
    $category = $post["category"];
    $expense = $post["expense"];

    $host = getenv('us-cdbr-iron-east-01.cleardb.net');
    $dbname = getenv('heroku_3d1f3b4f317ba1c');
    $password = getenv('9dfbf63f');
    $user = getenv('bea9ee3b212bdf');

   
        try{

            if ($_SERVER["REQUEST_METHOD"] !== "POST" || $post["button"] !== "click" ||
                empty($date) || empty($category) || empty($expense)
            ){
                header("location: myExpenses.php");
                exit;
            }

            // データベースに接続
            $PDO = new PDO("mysql:dbname=$dbname;host=$host;charset=utf8", $user, $password);
            $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // SQLでエラーが表示された場合、画面にエラーが出力される
            $PDO->setattribute(PDO::ATTR_EMULATE_PREPARES, false); // SQLインジェクション対策

            // データベースに書き込み
            $sql = "INSERT INTO expenses (date, category, expense) VALUES (?, ?, ?)";
            $stmt = $PDO->prepare($sql);
            $params = [$date, $category, $expense];
            $stmt->execute($params);

            // データベースのexpensesからデータを全て取得
            $sql = "SELECT * FROM expenses WHERE expense BETWEEN 10 AND 100000";
            $stmt = $PDO->query($sql);

            // データベースのexpenseの合計を取得
            $sum = "SELECT SUM(expense) AS total FROM expenses";
            $total_expenses = $PDO->query($sum);

            // データベースのカラムに名前を付けて、合計を取得
            $sum_food = "SELECT SUM(expense) AS total_food FROM expenses WHERE category = 'Food'";
            $total_food = $PDO->query($sum_food);

            $sum_snack_sweets = "SELECT SUM(expense) AS total_snack_sweets FROM expenses WHERE category = 'Snack/Sweets'";
            $total_snack_sweets = $PDO->query($sum_snack_sweets);

            $sum_dine_out = "SELECT SUM(expense) AS total_dine_out FROM expenses WHERE category = 'Dine_out'";
            $total_dine_out = $PDO->query($sum_dine_out);

            $sum_living_expenses = "SELECT SUM(expense) AS total_living_expenses FROM expenses WHERE category = 'Living_expenses'";
            $total_living_expenses = $PDO->query($sum_living_expenses);

            $sum_entertainment_expenses = "SELECT SUM(expense) AS total_entertainment_expenses FROM expenses WHERE category = 'Entertainment_expenses'";
            $total_entertainment_expenses = $PDO->query($sum_entertainment_expenses);

        }catch(PDOException $e) {

            exit("データベースに：接続出来ませんでした。".$e->getMessage());

        } 
    
    