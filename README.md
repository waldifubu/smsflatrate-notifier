Smsflatrate Notifier
=================

Provides [Smsflatrate](https://smsflatrate.net//) integration for Symfony Notifier.

DSN example
-----------
```
SMSFLATRATE_DSN=smsflatrate://www.smsflatrate.net?key=API_KEY&from=anonymous&type=auto3or4&flash=0&status=0
```
where:
- `key` is your Smsflatrate application key
- `from` is your number in international format
- `type` is your Gateway type. 
- `flash` shows SMS direct on screen
- `status` response with SMS id, to get details about transfer

API_KEY is mandatory, other options are optional

! All information here belongs to v4.1 of Smsflatrate API


Examples for attributes
-----------------------
- `API_KEY` like f4eba9a923a60dc6923a6b6bad1ab - no default
- `from` like 'anonymous' or 00491601234567 - see type default for details, default: anonymous
- `type` like 1,3,10,20 or 2,4,11,21. Details see below - default: auto3or4
- `flash` like 0 (off), 1 (on),  default: 0
- `status` like 0 (off), 1 (on), show as response `<statuscode>,<sms-id>` default: 0<br>
  To get status info of transfer, try this: https://www.smsflatrate.net/status.php?id=16207741492994858090 



Gateway types
-------------
General:
- Types for Germany only: 1, 3, 10
- Types to international receivers: 2, 4, 11
- Flash SMS is possible only for: 3, 10 (additional price and compatibility of receiver device)
- Reply option is possible to following types: 3, 4, 10, 11

Type 3 and 4 (low price) 
* are the most cheapest way because the `from` property is not available and chars length is limited to 735. Transfer priority is lower others. 
 
Type 1 and 2 (medium price)
* can set `from`, chars length can be up to 1071. Is is a little bit more expensive but faster.

Type 10 and 11 (highest level)
* The same as above, additionally the highest priority / should send faster than others. Recommened for alerts.

Just to be complete, it also exists a `time` parameter which tells server, when SMS should be sent.
In this bridge, this function ist not included.

Combinations
------------
You can combine types e.g.  
* type=auto1or2
* type=auto3or4
* type=auto10or11
* type=auto20or21

So using the proper type is a question of features and monetary decision
If you use it wrong, server gives a wrong status and SMS will not be sent

Statuscodes
-----------
In this section we reference to class SmsFlatrateStatus.

Status GATEWAY = 100;

Resources
---------

* [API Documentation in German](http://www.smsflatrate.net/download/schnittstelle.pdf)
* [Gateway information](http://www.smsflatrate.net/download/gateway.pdf)
* [Pricelist](http://www.smsflatrate.net/download/preise_noc.pdf)
* [Contributing](https://symfony.com/doc/current/contributing/index.html)
* [Report issues](https://github.com/symfony/symfony/issues) and
  [send Pull Requests](https://github.com/symfony/symfony/pulls)
  in the [main Symfony repository](https://github.com/symfony/symfony)
