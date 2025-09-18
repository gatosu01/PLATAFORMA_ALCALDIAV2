<?php
namespace App\Controllers;

class FaqController {
    public function index() {
        $faqPath = __DIR__ . '/../../DATA/faqs.json';
        $faqs = [];
        if (file_exists($faqPath)) {
            $faqsData = json_decode(file_get_contents($faqPath), true);
            if (is_array($faqsData)) {
                $faqs = $faqsData;
            }
        }
        require __DIR__ . '/../Views/faq.php';
    }
}
