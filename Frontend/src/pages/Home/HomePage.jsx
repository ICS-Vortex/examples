import React, {Suspense, useEffect} from "react";
import ReactHtmlParser from "react-html-parser";
import {Link} from 'react-router-dom';
import Image from 'react-bootstrap/Image'
import i18n from "../../i18n";
import {LANGUAGE_ENGLISH} from "../../constants/languages";
import LoadingComponent from "../../components/Loading/LoadingComponent";
import {Container} from "react-bootstrap";

const SliderComponent = React.lazy(() => import("../../components/Slider/SliderComponent"));
const ServersComponent = React.lazy(() => import("../../components/Servers/ServersComponent"));
const ArticlesComponent = React.lazy(() => import("../../components/Articles/ArticlesComponent"));
const CouponsModalComponent = React.lazy(() => import("../../components/Coupons/Modal/CouponsModalComponent"));

const HomePage = () => {
    const [tournament, setTournament] = React.useState({});

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/tournaments/current`)
            .then(r => r.json())
            .then(data => {
                setTournament(data);
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, []);

    return <React.Fragment>
        <Suspense fallback={<LoadingComponent/>}>
            <SliderComponent/>
        </Suspense>
        <Suspense fallback={<LoadingComponent/>}>
            <CouponsModalComponent/>
        </Suspense>
        {tournament?.id && !tournament.hidden && <div className="content text-center latest-tournament pt-5">
            <div className="title"><Link
                to={`/tournament/${tournament.id}/home`}>{i18n.t('label.tournament')} {i18n.language === LANGUAGE_ENGLISH ? tournament.titleEn : tournament.title}</Link>
            </div>
            <Link to={`/tournament/${tournament.id}/home`}>
                <Image thumbnail={true} className="w-95"
                       src={`${process.env.REACT_APP_API_HOST}/uploads/images/tournaments-banners/${i18n.language === LANGUAGE_ENGLISH ? tournament.bannerEn : tournament.banner}`}
                       fluid/>
            </Link>
        </div>}
        <div className="servers">
            <div className="content">
                <div className="title">{i18n.t('label.game_servers')}</div>
                <Suspense fallback={<LoadingComponent/>}>
                    <ServersComponent/>
                </Suspense>
            </div>
        </div>

        <div className="latest-news">
            <div className="content">
                <div className="title">{i18n.t('label.news_and_goods')}</div>
                <Container fluid>
                    <Suspense fallback={<LoadingComponent/>}>
                        <ArticlesComponent/>
                    </Suspense>
                </Container>
            </div>
        </div>

        <div className="bottom-section">
            <div className="content bottom-section__content">
                <div className="bottom-section__text text-white">
                    {ReactHtmlParser(i18n.t('message.help_conquer_sky'))}
                </div>
                <div className="bottom-section__logo"><img src="/images/logo.png" alt="VIRPIL servers"/></div>
            </div>
        </div>
    </React.Fragment>;
};

export default HomePage;
