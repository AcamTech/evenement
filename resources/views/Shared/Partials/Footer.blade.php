<div class="landscape">
    <div class="container footer-nav">
        <div class="row">
            @if ( Config::get('app.locale') == 'fr' )

                <div class="col-sm-4"><a href="https://www.canada.ca/fr/agence-revenu/organisation/coordonnees.html">@lang('basic.Contact us')</a></div>
                <div class="col-sm-4"><a href="https://www.canada.ca/fr/gouvernement/min.html">@lang('basic.Departments and agencies')</a></div>
                <div class="col-sm-4"><a href="https://www.canada.ca/fr/gouvernement/fonctionpublique.html">@lang('basic.Public service and military')</a></div>
                <div class="col-sm-4"><a href="https://www.canada.ca/fr/nouvelles.html">@lang('basic.News')</a></div>
                <div class="col-sm-4"><a href="https://www.canada.ca/fr/gouvernement/systeme/lois.html">@lang('basic.Treaties, laws and regulations')</a></div>
                <div class="col-sm-4"><a href="https://www.canada.ca/fr/transparence/rapports.html">@lang('basic.Government-wide reporting')</a></div>
                <div class="col-sm-4"><a href="http://pm.gc.ca/fra">@lang('basic.Prime Minister')</a></div>
                <div class="col-sm-4"><a href="https://www.canada.ca/fr/gouvernement/systeme.html">@lang('basic.How government works')</a></div>
                <div class="col-sm-4"><a href="http://ouvert.canada.ca/">@lang('basic.Open government')</a></div>

            @else

                <div class="col-sm-4"><a href="https://canada.ca/en/contact.html">@lang('basic.Contact us')</a></div>
                <div class="col-sm-4"><a href="https://canada.ca/en/government/dept.html">@lang('basic.Departments and agencies')</a></div>
                <div class="col-sm-4"><a href="https://canada.ca/en/government/publicservice.html">@lang('basic.Public service and military')</a></div>
                <div class="col-sm-4"><a href="https://canada.ca/en/news.html">@lang('basic.News')</a></div>
                <div class="col-sm-4"><a href="https://canada.ca/en/government/system/laws.html">@lang('basic.Treaties, laws and regulations')</a></div>
                <div class="col-sm-4"><a href="https://canada.ca/en/transparency/reporting.html">@lang('basic.Government-wide reporting')</a></div>
                <div class="col-sm-4"><a href="http://pm.gc.ca/eng">@lang('basic.Prime Minister')</a></div>
                <div class="col-sm-4"><a href="https://canada.ca/en/government/system.html">@lang('basic.How government works')</a></div>
                <div class="col-sm-4"><a href="http://open.canada.ca/en/">@lang('basic.Open government')</a></div>

           @endif

        </div>
    </div>
</div>
<div class="brand">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 brand-nav">
                <ul class="list-inline">


                    @if ( Config::get('app.locale') == 'fr' )
                        <li><a href="https://www.canada.ca/fr/sociaux.html">Médias sociaux</a></li>
                        <li><a href="https://www.canada.ca/fr/mobile.html">Applications mobiles</a></li>
                        <li><a href="https://www.canada.ca/fr/gouvernement/a-propos.html">À propos de Canada.ca</a></li>
                        <li><a href="https://www.canada.ca/fr/transparence/avis.html">Avis</a></li>
                        <li><a href="https://www.canada.ca/fr/transparence/confidentialite.html">Confidentialité</a></li>
                    @else

                        <li><a href="https://www.canada.ca/en/social.html">Social media</a></li>
                        <li><a href="https://www.canada.ca/en/mobile.html">Mobile applications</a></li>
                        <li><a href="https://www.canada.ca/en/government/about.html">About Canada.ca</a></li>
                        <li><a href="https://www.canada.ca/en/transparency/terms.html">Terms and conditions</a></li>
                        <li><a href="https://www.canada.ca/en/transparency/privacy.html">Privacy</a></li>
                    @endif
                </ul>
            </div>
            <div class="col-sm-2 brand-icon">
                <object
                        type="image/svg+xml"
                        tabindex="-1"
                        role="img"
                        data="/assets/images/wmms-blk.svg"
                        aria-label="Symbol of the Government of Canada"
                ></object>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                {{--Attendize is provided free of charge on the condition the below hyperlink is left in place.--}}
                {{--See https://www.attendize.com/license.html for more information.--}}
                @include('Shared.Partials.PoweredBy')
            </div>
        </div>
    </div>
</div>