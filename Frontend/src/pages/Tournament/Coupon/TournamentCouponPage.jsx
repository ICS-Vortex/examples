import React, {Suspense, useEffect} from 'react';
import i18n from "../../../i18n";
import {LANGUAGE_ENGLISH, LANGUAGE_RUSSIAN} from "../../../constants/languages";
import {Col, Row} from "react-bootstrap";
import LoadingComponent from "../../../components/Loading/LoadingComponent";

const ServerComponent = React.lazy(() => import('../../../components/Server/ServerComponent'));
const TournamentNavbarComponent = React.lazy(() => import('../../../components/Tournament/Navbar/TournamentNavbarComponent'));
const TournamentCouponTextEn = React.lazy(() => import('../../../components/Text/Tournament/Coupon/TournamentCouponTextEn'));
const TournamentCouponTextRu = React.lazy(() => import('../../../components/Text/Tournament/Coupon/TournamentCouponTextRu'));
const TournamentCouponForm = React.lazy(() => import('../../../components/Form/Tournament/Coupon/TournamentCouponForm'));

const TournamentCouponPage = (props) => {
    const [tournament, setTournament] = React.useState({});

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/tournaments/${parseInt(props.match.params.id)}/info`)
            .then(r => r.json())
            .then(data => {
                setTournament(data);
            })
            .catch(e => {
                setTournament({});
            })
        ;
    }, [])

    return (
        <React.Fragment>

            <main className="main">
                <div className="content text-center text-uppercase">
                    <h1>{i18n.language === LANGUAGE_ENGLISH ? tournament.titleEn : tournament.title}</h1>
                </div>
                {tournament.id && <div className="content">
                    <Suspense fallback={<LoadingComponent/>}>
                        <TournamentNavbarComponent tournament={tournament} active={'coupon'}/>
                    </Suspense>

                    <div className="main__content">
                        <Row>
                            <Col>
                                <div className="title">
                                    {i18n.language === LANGUAGE_ENGLISH ? '5% Discount' : '5% Скидка'}
                                </div>
                            </Col>
                        </Row>

                        <Row>
                            <Col xl={9} lg={9} md={7} sm={12} xs={12}>
                                {i18n.language === LANGUAGE_RUSSIAN &&
                                <Suspense fallback={<LoadingComponent/>}><TournamentCouponTextRu/></Suspense>}
                                {i18n.language === LANGUAGE_ENGLISH &&
                                <Suspense fallback={<LoadingComponent/>}><TournamentCouponTextEn/></Suspense>}

                                {tournament.id && <Suspense fallback={<LoadingComponent/>}><TournamentCouponForm
                                    tournament={tournament}/></Suspense>}
                            </Col>
                            <Col xl={3} lg={3} md={5} sm={12} xs={12}>
                                {tournament?.servers?.map((server) => (
                                    <Suspense key={server.id} fallback={<LoadingComponent/>}>
                                        <ServerComponent key={server.id} server={server}/>
                                    </Suspense>
                                ))}
                            </Col>
                        </Row>
                    </div>
                </div>}
            </main>
        </React.Fragment>
    );
};

export default TournamentCouponPage;