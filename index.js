var express = require('express');
var app = express();
let cors = require('cors');
const request = require('request');

app.use(express.static('.'));
app.use(cors());

let APIKey = 'moby_sdCDybSPYjxpemBsRXkaWEHTbpY';
let mobyUrl = 'https://api.mobygames.com/v1/games?api_key=';

app.get('/search', function (req, res) {

    let options = {
        method: 'GET',
        uri: mobyUrl + APIKey + '&' + req.url.slice(8)
    }

    console.log(options.uri);
    
    request.get(options, function(error, response, body){
        if(error){
            console.log(error);
        }else if(response.statusCode == 200){
            res.json(body);
        }else{
            console.log(response.statusCode);
        }
    });
});

app.get('/', function (req, res) {
    res.sendFile( __dirname + "/" + "home.html" );
});

app.get('/home', function (req, res) {
    res.sendFile(__dirname + "/" + "home.html");
});

app.get('/mylist', function (req, res) {
    res.sendFile(__dirname + "/" + "mylist.html");
});

app.get('/achievements', function (req, res) {
    res.sendFile(__dirname + "/" + "achievements.html");
});

app.get('/recommended', function (req, res) {
    res.sendFile(__dirname + "/" + "recommended.html");
});

app.get('/users', function (req, res) {
    res.sendFile(__dirname + "/" + "users.html");
});

var server = app.listen(8081, function () {
   var host = server.address().address
   var port = server.address().port
   
   console.log("Example app listening at http://localhost:%s", port)
});
