        <footer class="main-footer d-flex justify-content-between">
            {{-- Copyright section --}}
            <div class="d-inline-block">
                Copyright &copy; {{ date('Y') }} <a
                    href="{{ url('/') }}"><b>{{ config('app.name', 'Ctrlpanel.gg') }}</b></a>.
                All rights reserved.
                Powered by <a href="https://CtrlPanel.gg"><b>CtrlPanel</b></a> &copy; 2021-{{ date('Y') }}.
            </div>

            @if ($website_settings->show_imprint || $website_settings->show_privacy || $website_settings->show_tos)
                {{-- Show imprint and privacy link --}}
                <div class="d-none d-sm-inline-block">
                    @if ($website_settings->show_imprint)
                        <a target="_blank" href="{{ route('terms', 'imprint') }}"><strong>{{ __('Imprint') }}</strong></a>
                    @endif
                    @if (
                        ($website_settings->show_imprint && $website_settings->show_privacy) ||
                            ($website_settings->show_imprint && $website_settings->show_tos))
                        |
                    @endif
                    @if ($website_settings->show_privacy)
                        <a target="_blank" href="{{ route('terms', 'privacy') }}"><strong>{{ __('Privacy') }}</strong></a>
                    @endif
                    @if ($website_settings->show_privacy && $website_settings->show_tos)
                        |
                    @endif
                    @if ($website_settings->show_tos)
                        <a target="_blank"
                            href="{{ route('terms', 'tos') }}"><strong>{{ __('Terms of Service') }}</strong></a>
                    @endif
                </div>
            @endif
        </footer>
