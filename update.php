<?php
require_once "config.php";
$name = $nickname = $age = $race = $address = "";
$name_err = $nickname_err = $age_err = $race_err = $address_err = "";

if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];

    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }

    $input_nickname = trim($_POST["nickname"]);
    if(empty($input_nickname)){
        $nickname_err = "Please enter a nickname.";
    } else{
        $nickname = $input_nickname;
    }

    $input_age = trim($_POST["age"]);
    if(empty($input_age)){
        $age_err = "Please enter age.";     
    } elseif(!ctype_digit($input_age)){
        $age_err = "Please enter a positive integer value.";
    } else{
        $age = $input_age;
    }

    $input_race = trim($_POST["race"]);
    if(empty($input_race)){
        $race_err = "Please enter race.";
    } else{
        $race = $input_race;
    }
    
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }

    if(empty($name_err) && empty($nickname_err) && empty($age_err) && empty($race_err) && empty($address_err)){
        $sql = "UPDATE information SET name=?, nickname=?, age=?, race=?, address=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssssi", $param_name, $param_nickname, $param_age, $param_race, $param_address, $param_id);

            $param_name = $name;
            $param_nickname = $nickname;
            $param_age = $age;
            $param_race = $race;
            $param_address = $address;
            $param_id = $id;

            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }  
        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
} else{
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id =  trim($_GET["id"]);

        $sql = "SELECT * FROM information WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            $param_id = $id;

            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    $name = $row["name"];
                    $nickname = $row["nickname"];
                    $age = $row["age"];
                    $race = $row["race"];
                    $address = $row["address"];
                    
                } else{
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        mysqli_stmt_close($stmt);
        
        mysqli_close($link);
    }  else{
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Character</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 1400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Character</h2>
                    <p>Please edit the input values and submit to update the character information.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Nickname</label>
                            <input type="text" name="nickname" class="form-control <?php echo (!empty($nickname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nickname; ?>">
                            <span class="invalid-feedback"><?php echo $nickname_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>">
                            <span class="invalid-feedback"><?php echo $age_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Race</label>
                            <input type="text" name="race" class="form-control <?php echo (!empty($race_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $race; ?>">
                            <span class="invalid-feedback"><?php echo $race_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>