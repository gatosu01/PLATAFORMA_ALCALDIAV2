<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        // Conexión a la base de datos
        require_once __DIR__ . '/../../app/Models/Conexion.php';
        $conexion = (new \App\Models\Conexion())->getConexion();

        // Slider
        $slider_images_query = $conexion->query("SELECT * FROM slider_images ORDER BY position ASC");
        $slider_images = $slider_images_query ? $slider_images_query->fetchAll(\PDO::FETCH_ASSOC) : [];

        // Notificaciones
        $result = $conexion->query("SELECT titulo, mensaje FROM notifications ORDER BY fecha DESC LIMIT 3");
        $notificaciones = $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];

        // FAQs
        $faqPath = __DIR__ . '/../../DATA/faqs.json';
        $faqs = [];
        if (file_exists($faqPath)) {
            $faqsData = json_decode(file_get_contents($faqPath), true);
            if (is_array($faqsData)) {
                $faqs = $faqsData;
            }
        }
        $ultimasFaqs = !empty($faqs) ? array_slice($faqs, -3) : [];

        // Pasar variables a la vista
        extract([
            'slider_images' => $slider_images,
            'notificaciones' => $notificaciones,
            'ultimasFaqs' => $ultimasFaqs
        ]);
        require __DIR__ . '/../Views/home.php';
    }
}
