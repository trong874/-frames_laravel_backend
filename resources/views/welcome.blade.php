<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<textarea name="" id="text" cols="150" rows="50"></textarea>
<button id="edit">Edit</button>
<script>
    function readTextFile(file = '{{asset('css/app.css')}}')
    {
        var rawFile = new XMLHttpRequest();
        rawFile.open("GET", file, false);
        rawFile.onreadystatechange = function ()
        {
            if(rawFile.readyState === 4)
            {
                if(rawFile.status === 200 || rawFile.status == 0)
                {
                    document.getElementById('text').innerHTML = rawFile.responseText;
                }
            }
        }
        rawFile.send(null);
    }
    readTextFile()
    document.getElementById('edit').addEventListener('click',save);

    function makeTextFile(text) {
        var data = new Blob([text], {type: 'text/plain'});

        // If we are replacing a previously generated file we need to
        // manually revoke the object URL to avoid memory leaks.
        if (textFile !== null) {
            window.URL.revokeObjectURL(textFile);
        }

        textFile = window.URL.createObjectURL(data);

        return textFile;
    }
function save() {

}
</script>
</body>
</html>
