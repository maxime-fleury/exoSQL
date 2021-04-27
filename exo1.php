<script>
    function toggleEl(y){
        var x = document.getElementById(y);
        x.style.display = x.style.display === 'none' ? '' : 'none';
    }
</script>
<?php
require("config.php");

$conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
//tout les personnes dont le nom est palmer 1
$t = $conn->query("SELECT * FROM users WHERE last_name = 'Palmer'");
showU($t, "All with last_name = 'Palmer'", "palmer");

//toutes les femmes 2
$t = $conn->query("SELECT * FROM users WHERE gender = 'female'");
showU($t, "All Women", "women");

//toutes le pays commençant par N 3
$t = $conn->query("SELECT * FROM users WHERE country_code LIKE 'N%'");
showU($t, "Contries", "ncontries");

//tout les email contenant google 4
$t = $conn->query("SELECT * FROM users WHERE email LIKE '%google%'");
showU($t, "email containing google", "email");

//répartition par état et nombre par etat & ordre croissant 5
$t = $conn->query("SELECT *, COUNT(country_code) AS ord  FROM users GROUP BY country_code ORDER BY ord ASC");
echo "";
echo "<h3>Count countries by ASC<button onclick='toggleEl(`countcountries`)'>Hide/Show</button> </h3> ";
echo "<pre id='countcountries' style='display:none;'>";

while ($users = $t->fetch()) {
    echo $users['ord'] . " " .$users['country_code'] . "<br>";
}
echo "</pre>";

//insert into 6
$statement = "INSERT INTO users VALUES (NULL, 'Max', 'Fleury', 'maxfdev@gmail.com', 'Male', '127.0.0.1', '22/08/1996', '', 'http://avatar.com/img.jpg', '', 'FR')";
$h = $conn->exec($statement);
//affiche l'utilisateur qui vient d'être ajouté !
$t = $conn->query("SELECT * FROM users WHERE email = 'maxfdev@gmail.com'");
showU($t, "show added user", "addedUser");

//update from 6 
$statement = "UPDATE users SET email = 'newemail@gmail.com' WHERE email = 'maxfdev@gmail.com'";
$h = $conn->exec($statement);

//show updated email
$t = $conn->query("SELECT * FROM users WHERE email = 'newemail@gmail.com'");
showU($t, "show updated email", "updatedUser");

//delete from 6 BIS

$statement = "DELETE FROM users WHERE email = 'newemail@gmail.com'";
$h = $conn->exec($statement);

//affiche l'utilisateur qui vient d'être supprimé !
$t = $conn->query("SELECT * FROM users WHERE email = 'newemail@gmail.com'");
showU($t, "if nothing shows it worked and was deleted", "deletedUser");

//7 compter le nombre de femmes et d'hommes
$t = $conn->query("SELECT gender, count(gender) AS ord FROM users GROUP BY gender ORDER BY ord ASC");
echo "";
echo "<h3>Show women and men <button onclick='toggleEl(`genders`)'>Hide/Show</button></h3> ";
echo "<pre id='genders' style='display:none;'>";
    while ($users = $t->fetch()) {
        echo $users['ord']." ".$users["gender"]."<br>";
    }
echo "</pre>";
// afficher l'age chaque personnes, puis moyenne d'age général puis celle des femmes puis celle des hommes.
//age chaque personne
$t = $conn->query("SELECT last_name, first_name, gender, CAST(NOW() as date) as tee, STR_TO_DATE(birth_date, '%d/%m/%Y') as format_birth, ROUND(DATEDIFF(NOW(), STR_TO_DATE(birth_date, '%d/%m/%Y')) /365.25) as diff FROM users");

echo "";
echo "<h3>Show everyone's age <button onclick='toggleEl(`AllAGE`)'>Hide/Show</button></h3>";
echo "<pre id='AllAGE' style='display:none;'>";
    while ($users = $t->fetch()) {
        
        echo $users['last_name'] . " " . $users['first_name']. " " . $users['diff'] . " " . $users["gender"] . "<br>";
    }
echo "</pre>";
//moyenne d'age général :
$t = $conn->query("SELECT last_name, first_name, gender, 
round(AVG(ROUND(DATEDIFF(NOW(), STR_TO_DATE(birth_date, '%d/%m/%Y')) /365.25))) AS ord FROM users ORDER BY ord ASC");
echo "";
echo "<h3>Show average age <button onclick='toggleEl(`GlobalgendersAGE`)'>Hide/Show</button></h3>";
echo "<pre id='GlobalgendersAGE' style='display:none;'>";
    while ($users = $t->fetch()) {
        echo "Average age : ". $users['ord']."<br>";
    }
echo "</pre>";


//age par genre
$t = $conn->query("SELECT last_name, first_name, gender, 
round(AVG(ROUND(DATEDIFF(NOW(), STR_TO_DATE(birth_date, '%d/%m/%Y')) /365.25))) AS ord FROM users GROUP BY gender ORDER BY ord ASC");
echo "";
echo "<h3>Show average age by women and men <button onclick='toggleEl(`gendersAGE`)'>Hide/Show</button></h3> ";
echo "<pre id='gendersAGE' style='display:none;'>";
    while ($users = $t->fetch()) {
        echo "Average age : "  . $users['ord']." ".$users["gender"]."<br>";
    }
echo "</pre>";


//fonction d'affichage général
function showU($t, $h3_name, $_id){
    echo "";
    echo "<h3>$h3_name <button onclick='toggleEl(`$_id`)'>Hide/Show</button></h3> ";
    echo "<pre id='$_id' style='display:none;'>";
    
    while ($users = $t->fetch()) {
        echo $users['id'] . " " . $users['first_name'] . " " . $users['last_name'] . " " . $users['email'] . " " . $users['gender'] . " " . $users['country_code'] . "<br>";
    }
    echo "</pre>";
}

?>
