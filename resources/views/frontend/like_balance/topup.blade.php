<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ get_phrase('Top up balance') }}</h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('like_balance.process_topup') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label for="amount" class="form-label">{{ get_phrase('Amount') }}</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', 5000) }}" min="1000" step="1000" required>
                                <span class="input-group-text">{{ get_phrase('sum') }}</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h6>{{ get_phrase('Select amount') }}:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-primary text-white amount-btn" data-amount="5000" data-text-class="text-for-5000">5000 {{ get_phrase('sum') }}</button>
                                <button type="button" class="btn btn-outline-primary amount-btn" data-amount="10000" data-text-class="text-for-10000">10000 {{ get_phrase('sum') }}</button>
                                <button type="button" class="btn btn-outline-primary amount-btn" data-amount="30000" data-text-class="text-for-30000">30000 {{ get_phrase('sum') }} <span class="badge bg-success">+5000</span></button>
                                <button type="button" class="btn btn-outline-primary amount-btn" data-amount="50000" data-text-class="text-for-60000">50000 {{ get_phrase('sum') }} <span class="badge bg-success">+15000</span></button>
                                <button type="button" class="btn btn-outline-primary amount-btn" data-amount="100000" data-text-class="text-for-120000">100000 {{ get_phrase('sum') }} <span class="badge bg-success">+20000</span></button>
                            </div>
                        </div>
                        <div>
                            <p class="text-description text-for-5000">Siz bolalar yaxshiroq bilim olishi uchun yordam bera olasiz!

                                Bu paketdan tushgan daromad 100% bolalar uchun bepul sovg'alarga aylanadi.
                                Like orqali siz bilimga qiymat, farzandlarga ilhom berasiz.</p>
                                <p class="text-description text-for-10000" style="display: none;">
                                    Sizning har bir like'ingiz – bilimga bo'lgan ishonch!
                                    Bu paketdan tushgan mablag' 100% bolalarning bepul sovg'alariga yo'naltiriladi.
                                    Rag'batli like – o'qishga mehr, yutuqqa turtki!
                                </p>
                                <p class="text-description text-for-30000" style="display: none;">
                                    Ko'proq like – ko'proq rag'bat!
                                    <strong class="text-success">30,000 so'm to'lang, 35,000 so'mlik like balansi oling!</strong>
                                    Bu paketdan tushgan daromadning 100% qismi bolalarning bepul sovg'alariga sarflanadi.
                                    Siz ko'proq qo'llab-quvvatlayotganingiz sayin, bolalar ko'proq rag'bat oladi! </p>
                            <p class="text-description text-for-60000" style="display: none;">
                                katta rag'bat – bilimga ishonch!
                                <strong class="text-success">Bu paket sizga 15,000 so'mlik bonus bilan birga keladi: 50,000 so'm to'lab, 65,000 so'mlik like balansiga ega bo'lasiz!</strong>

                                Bu oddiy xarid emas. Bu – sizning bolalarga bo'lgan ishonchingiz, ularning mehnatiga bergan bahoyingiz.
                                Boqiy.uz'da siz bergan har bir pullik like 100% bolalarning bilimini qo'llab-quvvatlash uchun sovg'alarga aylanadi.

                                Farzandingiz yoki boshqa bir bola — bu like orqali o'zini qadrlangan his qiladi, yanada ko'proq o'qishga, yozishga, rivojlanishga intiladi.

                                Ko'proq like – ko'proq ilhom. Ko'proq rag'bat – ko'proq bilim!
                            </p>
                            <p class="text-description text-for-120000" style="display: none;">
                                Yagona xarid – yuzlab ilhom!
                                <strong class="text-success">100,000 so'm to'lab, 120,000 so'mlik like balansiga ega bo'ling!</strong>
                                Bu nafaqat katta bonus, balki katta imkoniyat: har bir berilgan like orqali siz bolalarning hayotiga ijobiy ta'sir ko'rsatasiz.

                                Boqiy.uz'da sizning har bir pullik like'ingiz 100% bepul sovg'alarga aylanadi. Bu sovg'alar esa bolalarning bilimga bo'lgan qiziqishini oshiradi, ularga kuch, ishonch va motivatsiya beradi.

                                Bu paket – bilimga sadoqat, kelajakka hissa!
                                Siz qo'llab-quvvatlagan har bir bola o'zini qadrlangan his qiladi.
                                Siz bergan 100 ming bugun kichik xarajatdek tuyulishi mumkin, ammo ularning kelajagida bu katta o'zgarish bo'ladi.

                                Yaxshi niyat bilan berilgan har bir like – yurt kelajagiga qo'shilgan mehrdir. </p>
                        </div>
                        
                        <div class="mb-4">
                            <h6>{{ get_phrase('Payment method') }}:</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="payme" value="payme" checked>
                                <label class="form-check-label" for="payme">
                                    {{ get_phrase('Payme') }}
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary">{{ get_phrase('Top up') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountBtns = document.querySelectorAll('.amount-btn');
        const amountInput = document.getElementById('amount');
        const textDescriptions = document.querySelectorAll('.text-description');
        
        // По умолчанию показываем только текст для 5000
        textDescriptions.forEach(text => {
            if (!text.classList.contains('text-for-5000')) {
                text.style.display = 'none';
            }
        });
        
        amountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const amount = this.getAttribute('data-amount');
                const textClass = this.getAttribute('data-text-class');
                amountInput.value = amount;
                
                // Remove active class from all buttons
                amountBtns.forEach(b => b.classList.remove('btn-primary', 'text-white'));
                amountBtns.forEach(b => b.classList.add('btn-outline-primary'));
                
                // Add active class to current button
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary', 'text-white');
                
                // Hide all descriptions
                textDescriptions.forEach(text => {
                    text.style.display = 'none';
                });
                
                // Show only the selected description
                if (textClass) {
                    const textToShow = document.querySelector('.' + textClass);
                    if (textToShow) {
                        textToShow.style.display = 'block';
                    }
                }
            });
        });
    });
</script>
@endpush 