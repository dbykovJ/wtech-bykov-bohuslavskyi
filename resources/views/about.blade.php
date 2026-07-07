@extends('layouts.app')

@section('title', 'Про нас і допомога — SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/about.css') }}" />
@endpush

@section('content')
<main class="info-main">
    <div class="container">
        <h1 class="info-title heading">ДОПОМОГА ТА ІНФОРМАЦІЯ</h1>

        <div class="info-layout">
            <aside class="info-nav">
                <div class="info-nav__section">
                    <div class="info-nav__section-title">Компанія</div>
                    <ul class="info-nav__list">
                        <li class="info-nav__item"><a data-scroll="about" class="info-nav__link">Про нас</a></li>
                        <li class="info-nav__item"><a data-scroll="features" class="info-nav__link">Можливості</a></li>
                        <li class="info-nav__item"><a data-scroll="works" class="info-nav__link">Як це працює</a></li>
                        <li class="info-nav__item"><a data-scroll="career" class="info-nav__link">Кар'єра</a></li>
                    </ul>
                </div>

                <div class="info-nav__section">
                    <div class="info-nav__section-title">Допомога</div>
                    <ul class="info-nav__list">
                        <li class="info-nav__item"><a data-scroll="customer-support" class="info-nav__link">Підтримка клієнтів</a></li>
                        <li class="info-nav__item"><a data-scroll="delivery-details" class="info-nav__link">Деталі доставки</a></li>
                        <li class="info-nav__item"><a data-scroll="terms" class="info-nav__link">Умови використання</a></li>
                        <li class="info-nav__item"><a data-scroll="privacy" class="info-nav__link">Політика конфіденційності</a></li>
                    </ul>
                </div>

                <div class="info-nav__section">
                    <div class="info-nav__section-title">Часті питання</div>
                    <ul class="info-nav__list">
                        <li class="info-nav__item"><a data-scroll="faq-account" class="info-nav__link">Акаунт</a></li>
                        <li class="info-nav__item"><a data-scroll="faq-manage-deliveries" class="info-nav__link">Керування доставками</a></li>
                        <li class="info-nav__item"><a data-scroll="faq-orders" class="info-nav__link">Замовлення</a></li>
                        <li class="info-nav__item"><a data-scroll="faq-payments" class="info-nav__link">Платежі</a></li>
                    </ul>
                </div>
            </aside>

            <section class="info-content">
                <article id="about" class="info-section">
                    <h2 class="info-section__title">Про нас</h2>
                    <div class="info-section__meta">Компанія &bull; Про SUPERSELL</div>
                    <div class="info-section__body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras et orci eget mi gravida ultricies. Nulla facilisi. Integer varius lorem at libero rutrum, sed aliquet nibh dictum. In dui mauris, blandit a suscipit sed, sodales in eros.</p>
                        <p>Nam congue, leo nec hendrerit fermentum, velit urna porttitor arcu, lacinia lacinia lorem ipsum a libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus vulputate, orci vel euismod venenatis, dui erat dignissim velit, a luctus sapien purus sed ipsum.</p>
                    </div>
                </article>

                <article id="features" class="info-section">
                    <h2 class="info-section__title">Можливості</h2>
                    <div class="info-section__meta">Компанія &bull; Можливості платформи</div>
                    <div class="info-section__body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus quis velit ac massa ultrices sollicitudin. Donec cursus diam nec nunc ornare, et vehicula leo auctor.</p>
                        <p>Aliquam erat volutpat. Vestibulum vitae urna mi. Suspendisse potenti. Sed ut libero pulvinar, vehicula neque id, finibus urna. Mauris condimentum massa elit, sed dictum lorem aliquet eget.</p>
                    </div>
                </article>

                <article id="works" class="info-section">
                    <h2 class="info-section__title">Як це працює</h2>
                    <div class="info-section__meta">Компанія &bull; Як працює SUPERSELL</div>
                    <div class="info-section__body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis non dolor nibh. Sed aliquet urna id nisl efficitur, nec bibendum eros placerat.</p>
                        <p>Curabitur auctor, nibh id aliquet sagittis, enim libero euismod leo, nec convallis libero ante id leo. Sed faucibus, lectus ac malesuada maximus, nunc purus volutpat lectus, sit amet cursus arcu risus vel lectus.</p>
                    </div>
                </article>

                <article id="career" class="info-section">
                    <h2 class="info-section__title">Кар'єра</h2>
                    <div class="info-section__meta">Компанія &bull; Кар'єра в SUPERSELL</div>
                    <div class="info-section__body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sodales leo eu purus posuere, in malesuada dui volutpat. Maecenas tempus urna eget est venenatis, at consequat lectus convallis.</p>
                        <p>Integer sed posuere elit. Phasellus sed libero lectus. Vivamus egestas consequat sodales. Maecenas euismod efficitur orci, at elementum neque rhoncus sed.</p>
                    </div>
                </article>

                <article id="customer-support" class="info-section">
                    <h2 class="info-section__title">Підтримка клієнтів</h2>
                    <div class="info-section__meta">Допомога &bull; Як отримати підтримку</div>
                    <div class="info-section__body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam convallis, metus in cursus feugiat, felis ligula pretium arcu, non condimentum mauris massa sed nulla.</p>
                        <p>Morbi aliquet, velit eget auctor dictum, tortor lectus porttitor eros, at mattis lectus risus id purus. Suspendisse potenti.</p>
                    </div>
                </article>

                <article id="delivery-details" class="info-section">
                    <h2 class="info-section__title">Деталі доставки</h2>
                    <div class="info-section__meta">Допомога &bull; Відправлення та доставка</div>
                    <div class="info-section__body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed fermentum, arcu at dignissim volutpat, sem dui interdum lacus, in euismod arcu nunc sit amet arcu.</p>
                        <p>Vestibulum quis fermentum mauris. Vivamus sed volutpat diam. Etiam hendrerit risus a velit finibus, id sodales lectus hendrerit.</p>
                    </div>
                </article>

                <article id="terms" class="info-section">
                    <h2 class="info-section__title">Умови використання</h2>
                    <div class="info-section__meta">Допомога &bull; Юридична інформація</div>
                    <div class="info-section__body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec imperdiet, nulla in rhoncus congue, ligula nisl auctor sapien, in elementum elit ipsum a lacus.</p>
                        <p>Integer orci urna, elementum eget interdum non, vehicula aliquet leo. Cras egestas, metus eu hendrerit scelerisque, orci tellus interdum urna, nec commodo justo leo id nunc.</p>
                    </div>
                </article>

                <article id="privacy" class="info-section">
                    <h2 class="info-section__title">Політика конфіденційності</h2>
                    <div class="info-section__meta">Допомога &bull; Конфіденційність і дані</div>
                    <div class="info-section__body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer at convallis lacus. Aliquam sollicitudin arcu id ipsum semper, a malesuada lacus laoreet.</p>
                        <p>Donec vitae tortor lacinia, sagittis libero non, rhoncus eros. Curabitur porttitor leo a orci pharetra, nec tempus urna tincidunt.</p>
                    </div>
                </article>

                <article id="faq-account" class="info-section">
                    <h2 class="info-section__title">Акаунт</h2>
                    <div class="info-section__meta">Часті питання &bull; Керування акаунтом</div>
                    <div class="info-section__body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum dictum, nibh at fermentum tincidunt, erat ligula vestibulum risus, a porta leo mi quis urna.</p>
                        <p>Morbi euismod arcu sed justo lacinia, sit amet varius est varius. Mauris eget commodo urna.</p>
                    </div>
                </article>

                <article id="faq-manage-deliveries" class="info-section">
                    <h2 class="info-section__title">Керування доставками</h2>
                    <div class="info-section__meta">Часті питання &bull; Питання щодо доставки</div>
                    <div class="info-section__body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean luctus, felis in pharetra lacinia, lectus arcu hendrerit mi, tincidunt congue mi enim at dui.</p>
                        <p>Integer ullamcorper sem eu consequat vestibulum. Nam molestie nibh eu pretium efficitur.</p>
                    </div>
                </article>

                <article id="faq-orders" class="info-section">
                    <h2 class="info-section__title">Замовлення</h2>
                    <div class="info-section__meta">Часті питання &bull; Відстеження замовлень</div>
                    <div class="info-section__body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed malesuada enim. Ut a sollicitudin lacus.</p>
                        <p>Nam sit amet magna sem. Nam rhoncus leo vel ante consequat, ut fermentum elit accumsan.</p>
                    </div>
                </article>

                <article id="faq-payments" class="info-section">
                    <h2 class="info-section__title">Платежі</h2>
                    <div class="info-section__meta">Часті питання &bull; Способи оплати</div>
                    <div class="info-section__body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin interdum pulvinar neque, a vestibulum eros molestie et.</p>
                        <p>Morbi dapibus facilisis mauris, at feugiat eros commodo nec. Cras nec erat in sem euismod placerat vitae nec turpis.</p>
                    </div>
                </article>
            </section>
        </div>
    </div>
</main>
@endsection
