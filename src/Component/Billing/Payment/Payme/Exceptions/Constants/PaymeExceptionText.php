<?php

declare(strict_types=1);

namespace Kadirov\Component\Billing\Payment\Payme\Exceptions\Constants;

class PaymeExceptionText
{
    public const ID_IS_NOT_FOUND_EN = 'Id is not found';
    public const ID_IS_NOT_FOUND_UZ = 'Tartib raqam topilmadi';
    public const ID_IS_NOT_FOUND_RU = 'ИД не найдена';

    public const ACCOUNT_IS_NOT_FOUND_EN = 'Account field is not found';
    public const ACCOUNT_IS_NOT_FOUND_UZ = 'Account qismi topilmadi';
    public const ACCOUNT_IS_NOT_FOUND_RU = 'Поле account не найдена ';

    public const WRONG_STATE_EN = 'Wrong state of transaction';
    public const WRONG_STATE_UZ = "Tranzaksiya statusi noto'g'ri";
    public const WRONG_STATE_RU = 'Не правильный статус транзакции';

    public const WRONG_AMOUNT_EN = 'Wrong amount';
    public const WRONG_AMOUNT_UZ = "To'lov summasi noto'g'ri";
    public const WRONG_AMOUNT_RU = 'Не правильная сумма';

    public const TRANSACTION_IS_NOT_FOUND_EN = 'Transaction is not found';
    public const TRANSACTION_IS_NOT_FOUND_UZ = 'Tranzaksiya topilmadi';
    public const TRANSACTION_IS_NOT_FOUND_RU = 'Транзакция не найдена';

    public const TRANSACTION_TIMEOUT_EN = 'Transaction timeout';
    public const TRANSACTION_TIMEOUT_UZ = 'Tranzaksiya eskirdi';
    public const TRANSACTION_TIMEOUT_RU = 'Транзакция устарела';

    public const TRANSACTION_ALREADY_HAS_PAYME_ID_EN = 'Transaction already has id';
    public const TRANSACTION_ALREADY_HAS_PAYME_ID_UZ = 'Tranzaksiya allaqachon id ga ega';
    public const TRANSACTION_ALREADY_HAS_PAYME_ID_RU = 'Транзакция уже имеет ID';

    public const TRANSACTION_FINISHED_AND_CANNOT_BE_CANCELED_EN = 'Transaction has finished and cannot be canceled';
    public const TRANSACTION_FINISHED_AND_CANNOT_BE_CANCELED_UZ = "Tranzaksiya bajarilgan. Bekor qilishning iloji yo'q";
    public const TRANSACTION_FINISHED_AND_CANNOT_BE_CANCELED_RU = 'Заказ выполнен и Невозможно отменить транзакцию';

    public const UNAUTHORIZED_EN = 'Unauthorized';
    public const UNAUTHORIZED_UZ = "Avtorizatsiyadan o'tmadingiz";
    public const UNAUTHORIZED_RU = 'Не авторизован';
}
