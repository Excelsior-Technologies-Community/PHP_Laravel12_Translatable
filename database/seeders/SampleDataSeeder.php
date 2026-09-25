<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $p1 = Post::create(['author' => 'Tech Team']);
        $p1->translateOrNew('en')->fill(['title' => 'Laravel 12 Release & Modern Features', 'content' => 'Laravel 12 brings enhanced performance, improved routing, and native support for modern PHP features.'])->save();
        $p1->translateOrNew('hi')->fill(['title' => 'लारवेल 12 रिलीज़ और आधुनिक विशेषताएं', 'content' => 'लारवेल 12 उन्नत प्रदर्शन, बेहतर रूटिंग और आधुनिक पीएचपी सुविधाओं के लिए देशी सहायता लाता है।'])->save();

        $p2 = Post::create(['author' => 'Anita Sharma']);
        $p2->translateOrNew('en')->fill(['title' => 'Building Multilingual Applications with Laravel Translatable', 'content' => 'Astrotomic Laravel Translatable simplifies storing localized content in separate translation tables seamlessly.'])->save();
        $p2->translateOrNew('hi')->fill(['title' => 'लारवेल ट्रांसलेटेबल के साथ बहुभाषी एप्लिकेशन बनाना', 'content' => 'एस्ट्रोटोमिक लारवेल ट्रांसलेटेबल अलग अनुवाद तालिकाओं में स्थानीयकृत सामग्री संग्रहीत करने को सरल बनाता है।'])->save();

        $p3 = Post::create(['author' => 'DevOps Circle']);
        $p3->translateOrNew('en')->fill(['title' => 'Best Security & Performance Practices for Web Applications', 'content' => 'Always implement CSRF protection, rate limiting, and honeypot traps to defend against automated bots.'])->save();
        $p3->translateOrNew('hi')->fill(['title' => 'वेब अनुप्रयोगों के लिए सर्वश्रेष्ठ सुरक्षा और प्रदर्शन पद्धतियाँ', 'content' => 'स्वचालित बॉट्स से रक्षा के लिए हमेशा सीएसआरएफ सुरक्षा, दर सीमित करना और हनीपॉट ट्रैप लागू करें।'])->save();

        $p4 = Post::create(['author' => 'Rajesh Patel']);
        $p4->translateOrNew('en')->fill(['title' => 'Mastering Database Migrations and Eloquent ORM', 'content' => 'Eloquent ORM provides a beautiful, simple ActiveRecord implementation for working with your database.'])->save();
        $p4->translateOrNew('hi')->fill(['title' => 'डेटाबेस माइग्रेशन और एलोक्वेंट ओआरएम में महारत हासिल करना', 'content' => 'एलोक्वेंट ओआरएम आपके डेटाबेस के साथ काम करने के लिए एक सुंदर, सरल एक्टिवरिकॉर्ड कार्यान्वयन प्रदान करता है।'])->save();

        $p5 = Post::create(['author' => 'Global News']);
        $p5->translateOrNew('en')->fill(['title' => 'Artificial Intelligence Trends in Modern Software Development', 'content' => 'AI tools and code assistants are transforming developer productivity and software engineering workflows.'])->save();
        $p5->translateOrNew('hi')->fill(['title' => 'आधुनिक सॉफ्टवेयर विकास में आर्टिफिशियल इंटेलिजेंस रुझान', 'content' => 'एआई उपकरण और कोड सहायक डेवलपर उत्पादकता और सॉफ्टवेयर इंजीनियरिंग कार्यप्रवाह को बदल रहे हैं।'])->save();

        $p6 = Post::create(['author' => 'Local Admin']);
        $p6->translateOrNew('en')->fill(['title' => 'Upcoming Developer Workshop and Live Coding Session', 'content' => 'Join our interactive workshop next weekend to explore cutting-edge Laravel features.'])->save();
    }
}
