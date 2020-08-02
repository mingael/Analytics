<html>
<head>
    <meta charset="utf-8">
    <title>Analytics</title>
</head>
<body>
<pre><?=print_r($_SERVER, true)?></pre>
</body>
<script language="javascript" src="collect.js"></script>
<script language="javascript">
collect('http://127.0.0.1/Analytics/collect.php');
</script>
</html>