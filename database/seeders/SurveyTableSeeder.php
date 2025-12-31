<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Survey;

class SurveyTableSeeder extends Seeder
{
    public function run()
    {
        // \App\Models\Survey::factory(10)->create(); 

        Survey::create([
            'title' => 'Website Rumah Sakit Husada',
            'slug' => 'website-rumah-sakit-husada',
            'image' => 'surveyFactory.jpg',
            'theme' => 'Website Rumah Sakit',
            'description' => 'This survey aims to gather feedback on the Husada Hospital website to enhance user experience and improve the overall design and functionality. Your input will help us create a more user-friendly and informative online platform for our visitors.',
            'url_website' => 'https://spmb.polinema.ac.id/info/halaman/detail/program-studi',
            'embed_design' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Ffile%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Ftype%3Ddesign%26node-id%3D6%253A5%26mode%3Ddesign%26t%3DMS0FsghvdchwVDS1-1',
            'embed_prototype' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fproto%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Fnode-id%3D6-5%26starting-point-node-id%3D6%253A5%26mode%3Ddesign%26t%3DqRgJWypwu4mOdUCX-1',
            'user_id' => 1,
            'status' => 'Public',
        ]);

        Survey::create([
            'title' => 'E-Commerce Fashion Store Revamp',
            'slug' => 'e-commerce-fashion-store-revamp',
            'image' => 'surveyFactory.jpg',
            'theme' => 'E-Commerce Fashion Aplication',
            'description' => 'Help us enhance the user interface and experience of our fashion e-commerce platform through this survey. Your valuable insights will guide us in redesigning the website to provide a more engaging and seamless shopping experience for our customers.',
            'url_website' => 'https://spmb.polinema.ac.id/info/halaman/detail/program-studi',
            'embed_design' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Ffile%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Ftype%3Ddesign%26node-id%3D6%253A5%26mode%3Ddesign%26t%3DMS0FsghvdchwVDS1-1',
            'embed_prototype' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fproto%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Fnode-id%3D6-5%26starting-point-node-id%3D6%253A5%26mode%3Ddesign%26t%3DqRgJWypwu4mOdUCX-1',
            'user_id' => 2,
            'status' => 'Public',
        ]);

        Survey::create([
            'title' => 'E-Learning Platform Enhancement',
            'slug' => 'e-learning-platform-enhancement',
            'image' => 'surveyFactory.jpg',
            'theme' => 'E-Learning Platform',
            'description' => 'Participate in this survey to provide feedback on our e-learning platform and help us enhance the user interface and experience. Your valuable input will contribute to improving the usability and effectiveness of our online learning environment.',
            'url_website' => 'https://spmb.polinema.ac.id/info/halaman/detail/program-studi',
            'embed_design' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Ffile%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Ftype%3Ddesign%26node-id%3D6%253A5%26mode%3Ddesign%26t%3DMS0FsghvdchwVDS1-1',
            'embed_prototype' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fproto%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Fnode-id%3D6-5%26starting-point-node-id%3D6%253A5%26mode%3Ddesign%26t%3DqRgJWypwu4mOdUCX-1',
            'user_id' => 3,
            'status' => 'Public',
        ]);

        Survey::create([
            'title' => 'Travel Booking App Redesign',
            'slug' => 'travel-booking-app-redesign',
            'image' => 'surveyFactory.jpg',
            'theme' => 'Travel Booking App',
            'description' => 'Take part in this survey to help us redesign our travel booking app for a better user experience. Your insights will guide us in creating a more intuitive and seamless booking platform for travelers.',
            'url_website' => 'https://spmb.polinema.ac.id/info/halaman/detail/program-studi',
            'embed_design' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Ffile%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Ftype%3Ddesign%26node-id%3D6%253A5%26mode%3Ddesign%26t%3DMS0FsghvdchwVDS1-1',
            'embed_prototype' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fproto%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Fnode-id%3D6-5%26starting-point-node-id%3D6%253A5%26mode%3Ddesign%26t%3DqRgJWypwu4mOdUCX-1',
            'user_id' => 4,
            'status' => 'Public',
        ]);

        Survey::create([
            'title' => 'Health and Fitness App User Feedback',
            'slug' => 'health-fitness-app-feedback',
            'image' => 'surveyFactory.jpg',
            'theme' => 'Health and Fitness App',
            'description' => 'Share your thoughts on our health and fitness app through this survey. Your feedback will assist us in enhancing the app\'s interface and user experience, making it more engaging and effective for users on their fitness journey.',
            'url_website' => 'https://spmb.polinema.ac.id/info/halaman/detail/program-studi',
            'embed_design' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Ffile%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Ftype%3Ddesign%26node-id%3D6%253A5%26mode%3Ddesign%26t%3DMS0FsghvdchwVDS1-1',
            'embed_prototype' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fproto%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Fnode-id%3D6-5%26starting-point-node-id%3D6%253A5%26mode%3Ddesign%26t%3DqRgJWypwu4mOdUCX-1',
            'user_id' => 3,
            'status' => 'Public',
        ]);

        Survey::create([
            'title' => 'Restaurant Website Redesign Survey',
            'slug' => 'restaurant-website-redesign',
            'image' => 'surveyFactory.jpg',
            'theme' => 'Restaurant Website',
            'description' => 'Participate in this survey to help us revamp our restaurant website for a better user experience. Your opinions will guide us in creating a visually appealing and user-friendly online platform for our customers.',
            'url_website' => 'https://spmb.polinema.ac.id/info/halaman/detail/program-studi',
            'embed_design' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Ffile%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Ftype%3Ddesign%26node-id%3D6%253A5%26mode%3Ddesign%26t%3DMS0FsghvdchwVDS1-1',
            'embed_prototype' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fproto%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Fnode-id%3D6-5%26starting-point-node-id%3D6%253A5%26mode%3Ddesign%26t%3DqRgJWypwu4mOdUCX-1',
            'user_id' => 1,
            'status' => 'Public',
        ]);

        Survey::create([
            'title' => 'Penilaian Pengalaman Pengguna Aplikasi Belanja Online',
            'slug' => 'penilaian-aplikasi-belanja-online',
            'image' => 'surveyFactory.jpg',
            'theme' => 'Aplikasi Belanja Online',
            'description' => 'Beri masukan Anda tentang pengalaman menggunakan aplikasi belanja online melalui survei ini. Pendapat Anda akan membantu kami meningkatkan antarmuka dan pengalaman pengguna, sehingga aplikasi menjadi lebih menarik dan efektif bagi pengguna dalam berbelanja secara online.',
            'url_website' => 'https://spmb.polinema.ac.id/info/halaman/detail/program-studi',
            'embed_design' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Ffile%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Ftype%3Ddesign%26node-id%3D6%253A5%26mode%3Ddesign%26t%3DMS0FsghvdchwVDS1-1',
            'embed_prototype' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fproto%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Fnode-id%3D6-5%26starting-point-node-id%3D6%253A5%26mode%3Ddesign%26t%3DqRgJWypwu4mOdUCX-1',
            'user_id' => 4,
            'status' => 'Private',
        ]);

        Survey::create([
            'title' => 'Survei Pembaruan Aplikasi Streaming Musik',
            'slug' => 'survei-pembaruan-aplikasi-musik',
            'image' => 'surveyFactory.jpg',
            'theme' => 'Aplikasi Streaming Musik',
            'description' => 'Ikut serta dalam survei ini untuk membantu kami memperbarui aplikasi streaming musik untuk pengalaman pengguna yang lebih baik. Masukan Anda akan membimbing kami dalam menciptakan platform yang lebih menarik dan mudah digunakan bagi para penggemar musik.',
            'url_website' => 'https://spmb.polinema.ac.id/info/halaman/detail/program-studi',
            'embed_design' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Ffile%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Ftype%3Ddesign%26node-id%3D6%253A5%26mode%3Ddesign%26t%3DMS0FsghvdchwVDS1-1',
            'embed_prototype' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fproto%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Fnode-id%3D6-5%26starting-point-node-id%3D6%253A5%26mode%3Ddesign%26t%3DqRgJWypwu4mOdUCX-1',
            'user_id' => 1,
            'status' => 'Public',
        ]);

        Survey::create([
            'title' => 'Penilaian Penggunaan Aplikasi Kesehatan Mental',
            'slug' => 'penilaian-aplikasi-kesehatan-mental',
            'image' => 'surveyFactory.jpg',
            'theme' => 'Aplikasi Kesehatan Mental',
            'description' => 'Berikan penilaian Anda tentang pengalaman menggunakan aplikasi kesehatan mental melalui survei ini. Masukan Anda sangat berarti bagi kami dalam meningkatkan antarmuka dan pengalaman pengguna aplikasi untuk memastikan kualitas layanan yang lebih baik.',
            'url_website' => 'https://spmb.polinema.ac.id/info/halaman/detail/program-studi',
            'embed_design' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Ffile%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Ftype%3Ddesign%26node-id%3D6%253A5%26mode%3Ddesign%26t%3DMS0FsghvdchwVDS1-1',
            'embed_prototype' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fproto%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Fnode-id%3D6-5%26starting-point-node-id%3D6%253A5%26mode%3Ddesign%26t%3DqRgJWypwu4mOdUCX-1',
            'user_id' => 1,
            'status' => 'Public',
        ]);

        Survey::create([
            'title' => 'Survei Pengalaman Pengguna Aplikasi Kuliner',
            'slug' => 'survei-aplikasi-kuliner',
            'image' => 'surveyFactory.jpg',
            'theme' => 'Aplikasi Kuliner',
            'description' => 'Ikuti survei ini untuk memberikan pengalaman Anda dalam menggunakan aplikasi kuliner. Masukan Anda akan membantu kami meningkatkan tampilan dan pengalaman pengguna aplikasi untuk memastikan layanan yang lebih baik bagi para pengguna.',
            'url_website' => 'https://spmb.polinema.ac.id/info/halaman/detail/program-studi',
            'embed_design' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Ffile%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Ftype%3Ddesign%26node-id%3D6%253A5%26mode%3Ddesign%26t%3DMS0FsghvdchwVDS1-1',
            'embed_prototype' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fproto%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Fnode-id%3D6-5%26starting-point-node-id%3D6%253A5%26mode%3Ddesign%26t%3DqRgJWypwu4mOdUCX-1',
            'user_id' => 2,
            'status' => 'Private',
        ]);
    }
}
