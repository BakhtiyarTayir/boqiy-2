<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>{{ get_phrase('Import Translations') }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
            <div class="eSection-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <p>{{ get_phrase('This will import translations from language files to the database. All phrases in the language files will be added to the database.') }}</p>
                                <p>{{ get_phrase('Files to import:') }}</p>
                                <ul>
                                    <li>resources/lang/en/likes.php</li>
                                    <li>resources/lang/uz/likes.php</li>
                                    <li>resources/lang/en/wallet.php</li>
                                    <li>resources/lang/uz/wallet.php</li>
                                    <li>resources/lang/en/general.php</li>
                                    <li>resources/lang/uz/general.php</li>
                                    <li>resources/lang/en/marketplace.php</li>
                                    <li>resources/lang/uz/marketplace.php</li>
                                    <li>resources/lang/en/main_content.php</li>
                                    <li>resources/lang/uz/main_content.php</li>
                                </ul>
                                <form action="{{ route('admin.import.translations') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">{{ get_phrase('Import Translations') }}</button>
                                </form>
                                
                                <div class="mt-4">
                                    <h5>{{ get_phrase('Default Language Settings') }}</h5>
                                    <p>{{ get_phrase('Current default language') }}: {{ get_settings('system_language') }}</p>
                                    <a href="{{ route('admin.set.default.language') }}" class="btn btn-success">{{ get_phrase('Set Uzbek as Default Language') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>