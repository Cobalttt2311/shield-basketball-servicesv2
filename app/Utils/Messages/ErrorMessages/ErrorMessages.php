<?php

namespace App\Utils\Messages\ErrorMessages;

class ErrorMessages
{
    const AUTH_INVALID_CREDENTIALS = 'Invalid credentials';
    const AUTH_UNAUTHORIZED        = 'Unauthorized';
    const AUTH_TOKEN_INVALID       = 'Token is invalid';
    const AUTH_TOKEN_EXPIRED       = 'Token has expired';
    const AUTH_TOKEN_NOT_PROVIDED  = 'Token not provided';
    const TOO_MANY_ATTEMPTS         = 'Terlalu banyak percobaan, silakan coba lagi nanti.';
    const INVALID_RESET_TOKEN       = 'Token reset password tidak valid atau sudah kadaluarsa.';
    const AUTH_UNKNOWN_ERROR        = 'Terjadi kesalahan, silakan coba lagi.';

    const USER_NOT_FOUND   = 'User not found';
    const COACH_NOT_FOUND  = 'Coach not found';
    const PLAYER_NOT_FOUND = 'Player not found';

    const CRITERIA_NOT_FOUND = 'Criteria not found';
    const SUBCRITERIA_NOT_FOUND = 'Sub criteria not found';
    const CRITERIA_DUPLICATE = 'Criteria already exists in this group';
    const SUBCRITERIA_DUPLICATE = 'Sub criteria already exists in this criteria';
    const CRITERIA_FORBIDDEN_GROUP = 'You cannot access criteria from another group';
    const EVALUATION_INVALID_SCORE = 'Score must be between 0 and 100';
    const EVALUATION_EMPTY_SCORES = 'Scores cannot be empty';
    const EVALUATION_NOT_FOUND = 'Evaluation not found';
}
