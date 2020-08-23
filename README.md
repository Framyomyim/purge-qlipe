# PHP Framework - Make for Front End Only!
 Open Source Framework PHP for only Front End
 
 It's work by module `modules/` is folder to keep others modules 
 How to is enter this command to your cmd `php server.php`

## First you need to see is env
```yaml
project:
  title: 'Simple Project With Purge Q-Lipe'
  url: 'http://localhost:86/PurgeQLipe/'

port: 8080
```
You should set all datas in this file.

## Second you need to see routes.js
```json
[
    {
        "pattern": "/",
        "module": "home.default"
    }
]
```
This is a basic router you can add it, see example.
```json
[
    {
        "pattern": "/",
        "module": "home.default"
    },
    {
        "pattern": "/page-2",
        "module": "home.default"
    }
]
```
Yeah, something like this.


## Next in your module should be have ...
In your folder shoud be have importance folders are `public/` and `config/`
__public/__ is for your html file or assets
__config/__ is for set path css or js it will help you easy! you can see example in `modules/home/config/default.json` and it work in `modules/home/public/default.html`
