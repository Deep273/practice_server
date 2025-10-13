<?php
//
//namespace Src\Validator;
//
//class PhoneValidator
//{
//    protected string $phone;
//    protected array $errors = [];
//
//    public function __construct(?string $phone)
//    {
//        $this->phone = trim($phone ?? '');
//        $this->validate();
//    }
//
//    protected function validate(): void
//    {
//        // Проверяем, что не пустое
//        if ($this->phone === '') {
//            $this->errors[] = 'Номер телефона обязателен для заполнения.';
//            return;
//        }
//
//        // Проверяем допустимые символы — цифры, +, -, пробелы и скобки
//        if (!preg_match('/^[0-9+\-\s()]+$/', $this->phone)) {
//            $this->errors[] = 'Номер телефона может содержать только цифры, пробелы, +, -, ().';
//        }
//
//        // Проверяем минимальную длину (например, 10 символов)
//        $digitsOnly = preg_replace('/\D/', '', $this->phone); // оставляем только цифры
//        if (strlen($digitsOnly) < 10) {
//            $this->errors[] = 'Номер телефона должен содержать минимум 10 цифр.';
//        }
//
//        // Проверяем, чтобы не было слишком длинным (например, не больше 15 цифр)
//        if (strlen($digitsOnly) > 15) {
//            $this->errors[] = 'Номер телефона слишком длинный.';
//        }
//    }
//
//    public function fails(): bool
//    {
//        return !empty($this->errors);
//    }
//
//    public function errors(): array
//    {
//        return $this->errors;
//    }
//}
