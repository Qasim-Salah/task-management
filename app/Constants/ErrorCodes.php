<?php


namespace App\Constants;


final class ErrorCodes
{

    //Client
  const VALIDATION = 100;
  const AUTH_FAILED = 101;
  const OTP_CODE_INVALID = 102;
  const UNACTIVATED = 103;
  const ACTIVATED = 104;

    // APPS
  const platformNotExistHeader = 200;
  const platformNotExist = 201;
  const appVersionNotExistHeader = 202;
  const appVersionNotExist = 203;

    //Server
  const OTP_ATTEMPTS_RESEND = 300;
  const TOO_MANY_REQUESTS = 301;
  const app_Update = 302;

}
