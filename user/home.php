<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sistem Pembuatan SKCK Online Polsek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar styling */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 230px;
            background: #343a40;
            color: #ced4da;
            display: flex;
            flex-direction: column;
            padding: 1rem 1rem 2rem;
        }

        #sidebar .user-name {
            font-weight: 600;
            color: #fff;
            font-size: 1.2rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid #495057;
            padding-bottom: 0.5rem;
        }

        #sidebar .nav-link {
            color: #adb5bd;
            font-weight: 500;
            margin-bottom: 0.5rem;
            border-radius: 0.3rem;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background-color: #495057;
            color: #fff;
        }

        /* Top header */
        #top-header {
            margin-left: 230px;
            background-color: #0d6efd;
            color: white;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.25rem;
            box-shadow: 0 2px 6px rgb(0 0 0 / 0.15);
            position: sticky;
            top: 0;
            z-index: 1040;
        }

        /* Content container */
        #content {
            margin-left: 230px;
            padding: 1.5rem 2rem 3rem;
            background-color: #f8f9fa;
            min-height: calc(100vh - 56px);
        }

        /* Judul konten */
        #content h2 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #212529;
        }

        /* Berita utama */
        .main-news {
            display: flex;
            flex-direction: column;
            margin-bottom: 2rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 3px 7px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .main-news img {
            width: 100%;
            height: 320px;
            object-fit: cover;
        }

        .main-news .news-body {
            padding: 1rem 1.5rem 1.5rem;
        }

        .main-news .news-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #0d6efd;
            cursor: pointer;
        }

        .main-news .news-date {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .main-news .news-summary {
            font-size: 1rem;
            color: #343a40;
            line-height: 1.4;
        }

        .main-news:hover {
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
            transition: box-shadow 0.3s ease;
        }

        /* Berita kecil di bawah */
        .small-news-list {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .small-news {
            background: white;
            flex: 1 1 280px;
            display: flex;
            border-radius: 0.5rem;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: box-shadow 0.3s ease;
            overflow: hidden;
            max-width: 380px;
            min-width: 280px;
        }

        .small-news:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .small-news img {
            width: 110px;
            object-fit: cover;
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
        }

        .small-news .news-body {
            padding: 0.75rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex-grow: 1;
        }

        .small-news .news-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: #0d6efd;
        }

        .small-news .news-date {
            font-size: 0.75rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .small-news .news-summary {
            font-size: 0.9rem;
            color: #495057;
            line-height: 1.2;
        }

        @media (max-width: 768px) {
            #sidebar {
                position: relative;
                width: 100%;
                height: auto;
                flex-direction: row;
                padding: 0.5rem 1rem;
                align-items: center;
            }

            #sidebar .user-name {
                margin-bottom: 0;
                margin-right: 1rem;
                border-bottom: none;
                font-size: 1rem;
            }

            #sidebar .nav {
                flex-direction: row;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            #content {
                margin-left: 0;
                padding: 1rem;
            }

            #top-header {
                margin-left: 0;
                padding: 1rem;
                font-size: 1.1rem;
            }

            .small-news-list {
                justify-content: center;
            }

            .small-news {
                max-width: 100%;
                min-width: auto;
                flex-basis: 100%;
            }
        }
    </style>
</head>

<body>

    <nav id="sidebar" class="shadow">
        <div class="user-name">Selamat datang, Budi</div>
        <nav class="nav flex-column">
            <a href="index.php?page=home" class="nav-link active">Home</a>
            <a href="index.php?page=pengajuan" class="nav-link">Pengajuan SKCK</a>
            <a href="logout.php" class="nav-link text-danger">Logout</a>
        </nav>
    </nav>

    <header id="top-header">
        Sistem Pembuatan SKCK Online Polsek
    </header>

    <main id="content">
        <h2>Update Berita Polsek</h2>

        <article class="main-news" tabindex="0" role="article" aria-label="Berita utama">
            <img src=https://peloporwiratama.co.id/wp-content/uploads/2023/03/WhatsApp-Image-2023-03-07-at-14.58.06.jpeg" />
            <!-- <img src=https://dummyimage.com/600x400/777/fff.jpg&text=Sample+Image" /> -->
            <div class="news-body">
                <h3 class="news-title">Polsek Jadi Pelopor Kampung Aman</h3>
                <time class="news-date" datetime="2023-06-01">1 Juni 2023</time>
                <p class="news-summary">
                    Polsek setempat menginisiasi program kampung aman untuk meningkatkan rasa aman dan nyaman
                    masyarakat.
                </p>
            </div>
        </article>

        <section class="small-news-list" aria-label="Berita kecil">
            <article class="small-news" tabindex="0" role="article" aria-label="Berita Polsek Bagikan Masker Gratis">
                <img src=https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRuLRFwH2MdNTX9wun5ya0KI51ws7ujyR9PpQ&s" />
                <!-- <img src=https://dummyimage.com/600x400/777/fff.jpg&text=Sample+Image" /> -->
                <div class="news-body">
                    <h4 class="news-title">Polsek Bagikan Masker Gratis</h4>
                    <time class="news-date" datetime="2023-05-28">28 Mei 2023</time>
                    <p class="news-summary">Dalam rangka pemutusan mata rantai pandemi, Polsek melakukan pembagian
                        masker gratis kepada warga.</p>
                </div>
            </article>

            <article class="small-news" tabindex="0" role="article"
                aria-label="Patroli Malam Polsek Meningkatkan Keamanan">
                <img src="https://jogja.polri.go.id/yogyakarta/file/thumbnail_431x488/Screenshot-83-6.jpeg" />
                <!-- <img src=https://dummyimage.com/600x400/777/fff.jpg&text=Sample+Image" /> -->
                <div class="news-body">
                    <h4 class="news-title">Patroli Malam Polsek Meningkatkan Keamanan</h4>
                    <time class="news-date" datetime="2023-05-15">15 Mei 2023</time>
                    <p class="news-summary">Polsek rutin melakukan patroli malam guna menjaga ketertiban dan mengurangi
                        tindak kriminalitas.</p>
                </div>
            </article>

            <article class="small-news" tabindex="0" role="article" aria-label="Penyaluran Bantuan Sosial Oleh Polsek">
                <img src="https://tribratanews.sumsel.polri.go.id/assets/artikel/41ad7a361cf7dcf1ec4facfd44f86138.jpg" />
                <!-- <img src=https://dummyimage.com/600x400/777/fff.jpg&text=Sample+Image" /> -->
                <div class="news-body">
                    <h4 class="news-title">Penyaluran Bantuan Sosial Oleh Polsek</h4>
                    <time class="news-date" datetime="2023-04-20">20 April 2023</time>
                    <p class="news-summary">Polsek menyalurkan bantuan sosial kepada warga terdampak bencana untuk
                        meringankan beban masyarakat.</p>
                </div>
            </article>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>