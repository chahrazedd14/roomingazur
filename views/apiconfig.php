<?php
const DOMAIN = "https://mmvrooming.azurewebsites.net/";
const APP_SESS_ID = 'rooming';
const OAUTH_APP_ID = '02e829e6-ba10-41c9-a60b-10f79b7d7dcc';
const OBJECT_ID = 'c0ef96a0-6766-43b1-bc95-bbc649d355d7';
const CLIENT_SECRET_VALUE = 'g6W8Q~tSEXBYIgmRviI2av2xfLQpGbA2rHExhbtu';
const TENANT_ID = "2ae77e8b-d7b3-4d51-8130-f9c216107799";
// const OAUTH_REDIRECT_URI = 'http://localhost/rooming/views/dashadmin.html';
const OAUTH_REDIRECT_URI = DOMAIN.'views/dashadmin.php';
const OAUTH_SCOPES = 'openid profile offline_access user.read';
const OAUTH_AUTHORITY = 'https://login.microsoftonline.com/' . TENANT_ID;
const OAUTH_AUTHORIZE_ENDPOINT = '/oauth2/v2.0/authorize';
const OAUTH_TOKEN_ENDPOINT = '/oauth2/v2.0/token';

