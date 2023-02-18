<!DOCTYPE html>

<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Full Name</title>
</head>
<body>
{{csrf_field()}}


First Name: <b> {{ $onoma }}</b><br>
Last Name: <b> {{ $epitheto }}</b><br>


<br>

<form method="post" action='/exagogi'>

    
    <input type="submit" value="Export to Word" />   //I want to press Export to Word key to produce doc.
</form>

</body>
</html>