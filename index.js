var express = require('express');
var app = express();
let cors = require('cors');
const request = require('request');

app.use(express.static('.'));
app.use(cors());

let APIKey = 'moby_sdCDybSPYjxpemBsRXkaWEHTbpY';
let mobyUrl = 'https://api.mobygames.com/v1/games?api_key=moby_sdCDybSPYjxpemBsRXkaWEHTbpY';

app.get('/search', function (req, res) {

    let options = {
        method: 'GET',
        uri: 'https://api.mobygames.com/v1/games?api_key=moby_sdCDybSPYjxpemBsRXkaWEHTbpY&title=pok%C3%A9mon',
        path: '',
    }
    
    request.get(options, function(error, response, body){
        if(error){
            console.log(error);
        }else if(response.statusCode == 200){
            
            console.log(JSON.parse(body));
            res.json(body);
        }else{
            console.log(response.statusCode);
        }
    });
    // res.send();

    // let url = mobyUrl + APIKey + '&title=' + 'crystal';
    // console.log(url);

    // fetch(url)
    // .then(function(response){ return response.body})
    // .then(function(body){
    //     // Read body of response
    //     const reader = body.getReader();
    //     return new ReadableStream({
    //         start(controller) {
    //             return pump();
    //             function pump() {
    //                 return reader.read().then(function({ done, value }){
    //                     // When no more data needs to be consumed, close the stream
    //                     if (done) {
    //                         controller.close();
    //                         return;
    //                     }
    //                     // Enqueue the next data chunk into our target stream
    //                     controller.enqueue(value);
    //                     return pump();
    //                 }).then(function(){reader.releaseLock()});
    //             }
    //         },
    //     })
    // })
    // .then(function(stream){
    //     console.log(stream);
    //     return new Response(stream);
    // })
    // .then(function(response){
    //     console.log(response);
    //     console.log(JSON.stringify(response));
    //     res.json(response);
    // })
    // .catch(function(error){
    //     console.log(error);
    // });
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
