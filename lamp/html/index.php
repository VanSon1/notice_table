<?php

define( 'FILENAME', './message.txt');

date_default_timezone_set('Asia/Tokyo');

$current_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
$clean = array();



// luu du lieu vao file
if( !empty($_POST['btn_submit']) ) {

	if( empty($_POST['view_name']) ) {
		$error_message[] = 'name error';
	} else {
        $clean['view_name'] = htmlspecialchars( $_POST['view_name'], ENT_QUOTES, 'UTF-8');
        $clean['view_name'] = preg_replace( '/\\r\\n|\\n|\\r/', '', $clean['view_name']);
    }
	
	if( empty($_POST['message']) ) {
		$error_message[] = 'message error';
	}  else {
        $clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES, 'UTF-8');
        $clean['message'] = preg_replace( '/\\r\\n|\\n|\\r/', '<br>', $clean['message']);
    }

	if( empty($error_message) ) {

		if( $file_handle = fopen( FILENAME, "a") ) {
	
			$current_date = date("Y-m-d H:i:s");
		
//			$data = "'".$_POST['view_name']."','".$_POST['message']."','".$current_date."'\n";
            $data = "'".$clean['view_name']."','".$clean['message']."','".$current_date."'\n";
			fwrite( $file_handle, $data);
		
			fclose( $file_handle);
	
			$success_message = 'comment success';
		}
	}
}


if(isset($_POST)) {
    echo ($_POST[`view_name`]);
    echo `<br/>`;
    echo ($_POST[`message`]);
}


if( $file_handle = fopen( FILENAME,'r') ) {
    while( $data = fgets($file_handle) ){

		$split_data = preg_split( '/\'/', $data);

		$message = array(
			'view_name' => $split_data[1],
			'message' => $split_data[3],
			'post_date' => $split_data[5]
		);
		array_unshift( $message_array, $message);
	}
    
    // ファイルを閉じる
    fclose( $file_handle);
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <link rel="stylesheet" href="style.css">
<meta charset="utf-8">
<title>ひと言掲示板</title>
</head>
<body>
<h1>ひと言掲示板 (Alert table)</h1>


<!-- hien thi trang thai cua viec gui dong gop  -->
<?php if( !empty($success_message) ): ?>
    <p class="success_message"><?php echo $success_message; ?></p> 
<?php endif; ?>
<?php if( !empty($error_message) ): ?>
    <ul class="error_message">
		<?php foreach( $error_message as $value ): ?>
            <li>・<?php echo $value; ?></li>
		<?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- tao form de gui du lieu len server -->
<form action="" method="post">
	<div>
		<label for="view_name">User name</label>
		<input id="view_name" type="text" name="view_name" value="">
	</div>
	<div>
		<label for="message">Comments</label>
		<textarea id="message" name="message"></textarea>
	</div>
	<input type="submit" name="btn_submit" value="Submit">

</form>

<!-- hien thi tat ca cac dong gop truoc do -->
<section>
<?php if( !empty($message_array) ){ ?>
<?php foreach( $message_array as $value ){ ?>
<article>
    <div class="info">
        <h2><?php echo $value['view_name']; ?></h2>
        <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
    </div>
    <p><?php echo $value['message']; ?></p>
</article>
<?php } ?>
<?php } ?>
</section>
</body>
</html>
