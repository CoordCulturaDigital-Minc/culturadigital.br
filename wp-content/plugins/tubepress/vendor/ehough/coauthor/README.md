# coauthor [![Build Status](https://secure.travis-ci.org/ehough/coauthor.png)](http://travis-ci.org/ehough/coauthor)

OAuth library designed for `ehough/shortstop` integration.

### Features

* Adheres as closely as possible to [RFC 5894](http://tools.ietf.org/html/rfc5849)
* Excellent test coverage
* OAuth 1.x only - for now

### Sample Usage

```php
//instance of ehough_shortstop_api_HttpClientInterface
$httpClient = ...

//instance of ehough_coauthor_spi_v1_TemporaryCredentialsStorageInterface
$credentialsStorage = new ehough_coauthor_impl_v1_SessionCredentialsStorage();

//instance of ehough_coauthor_spi_v1_SignerInterface
$signer = new ehough_coauthor_impl_v1_Signer();

//instance of ehough_coauthor_spi_v1_RemoteCredentialsFetcherInterface
$credentialsFetcher = new ehough_coauthor_impl_v1_DefaultRemoteCredentialsFetcher($httpClient, $signer);

//instance of ehough_coauthor_api_v1_ClientInterface
$client = new ehough_coauthor_impl_v1_DefaultV1Client($credentialsFetcher, $credentialsStorage, $signer);

//build client credentials
$clientCredentials = new ehough_coauthor_api_v1_Credentials('identifier', 'secret');


//will fetch and store temporary credentials in a PHP session, then redirect to the given URL
$client->commenceNewAuthorization($server, 'http://something.com/oauth/fetchTokens.php', $clientCredentials);

OR

//will fetch new token/access credentials
$token = $client->fetchTokenCredentials($server, 'credentials-id', 'verification-code', $clientCredentials);

OR

//will sign a single HTTP request, with the optional $token credentials
$client->sign($request, $clientCredentials, $token);
```