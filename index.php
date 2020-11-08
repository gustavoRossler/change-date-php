<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Change Date Test</title>
</head>

<body>

    <div class="container">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Change Date Test</h1>
                <p class="lead">Use the form below to test the changeDate function implementation!</p>
            </div>
        </div>

        <form>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="text" class="form-control" id="date" aria-describedby="dateHelp">
                <small id="dateHelp" class="form-text text-muted">Date must be in the format "dd/MM/yyyy HH24:mi"</small>
            </div>
            <div class="form-group">
                <label for="op">Operator</label>
                <input type="text" class="form-control" id="op" aria-describedby="opHelp">
                <small id="opHelp" class="form-text text-muted">Operator must be either "+" or "-"</small>
            </div>
            <div class="form-group">
                <label for="value">Value</label>
                <input type="number" class="form-control" id="value" aria-describedby="valueHelp">
                <small id="valueHelp" class="form-text text-muted">Value in minutes</small>
            </div>
            <p class="text-center">
                <button type="button" onclick="testMethod()" class="btn btn-primary">Test method</button>
            </p>
            <p>
                <div id="result" class="alert alert-info" role="alert"></div>
            </p>
            <p>
                <div id="error" class="alert alert-danger" role="alert"></div>
            </p>

        </form>

    </div>

    <script>
        const testMethod = async () => {
            clearResults();
            document.getElementById('result').textContent = 'Loading...';

            const form = new FormData();
            form.append('date', document.getElementById('date').value);
            form.append('value', document.getElementById('value').value);
            form.append('op', document.getElementById('op').value);

            const res = await fetch('processData.php', {
                method: 'POST',
                body: form
            });
            const json = await res.json();

            if (json.success == true) {
                document.getElementById('result').style.display = 'block';
                document.getElementById('result').textContent = 'Result: ' + json.data.resultDate;
            } else {
                document.getElementById('result').textContent = '';
                document.getElementById('error').style.display = 'inherit';
                document.getElementById('error').textContent = json.error;
            }
        }

        const clearResults = () => {
            document.getElementById('result').textContent = '';
            document.getElementById('error').textContent = '';
            document.getElementById('error').style.display = 'none';
            document.getElementById('result').style.display = 'none';
        }

        clearResults();
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
</body>

</html>