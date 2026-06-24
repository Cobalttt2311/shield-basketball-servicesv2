<?php

namespace App\Utils\Messages\ErrorMessages;

class ErrorMessages
{
    const AUTH_INVALID_CREDENTIALS = 'Invalid credentials';

    const AUTH_UNAUTHORIZED = 'Unauthorized';

    const AUTH_TOKEN_INVALID = 'Token is invalid';

    const AUTH_TOKEN_EXPIRED = 'Token has expired';

    const AUTH_TOKEN_NOT_PROVIDED = 'Token not provided';

    const TOO_MANY_ATTEMPTS = 'Too many attempts, please try again later.';

    const INVALID_RESET_TOKEN = 'The password reset token is invalid or has expired.';

    const AUTH_UNKNOWN_ERROR = 'An error occurred, please try again.';

    const USER_NOT_FOUND = 'User not found';

    const COACH_NOT_FOUND = 'Coach not found';

    const PLAYER_NOT_FOUND = 'Player not found';

    const CRITERIA_NOT_FOUND = 'Criteria not found';

    const SUBCRITERIA_NOT_FOUND = 'Sub criteria not found';

    const CRITERIA_DUPLICATE = 'Criteria already exists in this group';

    const SUBCRITERIA_DUPLICATE = 'Sub criteria already exists in this criteria';

    const CRITERIA_FORBIDDEN_GROUP = 'You cannot access criteria from another group';

    const EVALUATION_INVALID_SCORE = 'Score must be between 0 and 100';

    const EVALUATION_EMPTY_SCORES = 'Scores cannot be empty';

    const EVALUATION_NOT_FOUND = 'Evaluation not found';

    const GROUP_NOT_FOUND = 'Group not found';

    const POSITION_NOT_FOUND = 'Position not found';

    const EVALUATION_SCORES_NOT_FOUND = 'Evaluation scores not found for this sub criteria';

    const CRITERIA_WEIGHTS_NOT_FOUND = 'Criteria weights not found for this position. Please calculate and save criteria weights first.';

    const SUBCRITERIA_WEIGHTS_NOT_FOUND = 'Sub criteria weights not found for this position. Please calculate and save sub criteria weights first.';

    const FORBIDDEN_HEAD_COACH = 'Forbidden - only Head Coach can perform this action';

    const REPORT_NOT_FOUND = 'Evaluation report not found';
}
