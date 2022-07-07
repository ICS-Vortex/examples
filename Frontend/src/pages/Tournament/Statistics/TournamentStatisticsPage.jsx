import React, {Suspense, useEffect} from 'react';
import {Col, Nav, Row} from "react-bootstrap";
import i18n from "../../../i18n";
import {LANGUAGE_ENGLISH} from "../../../constants/languages";
import Tab from 'react-bootstrap/Tab';
import LoadingComponent from "../../../components/Loading/LoadingComponent";

const TournamentNavbarComponent = React.lazy(() => import('../../../components/Tournament/Navbar/TournamentNavbarComponent'));
const PilotsRacingRankingComponent = React.lazy(() => import('../../../components/Racing/PilotsRacingRankingComponent'));
const ServerComponent = React.lazy(() => import('../../../components/Server/ServerComponent'));

const TournamentStatisticsPage = (props) => {
    const id = parseInt(props.match.params.id);
    const [tournament, setTournament] = React.useState({});

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/tournaments/${id}/info`)
            .then(r => r.json())
            .then((data) => {
                setTournament(data);
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, []);
    return (
        <main className="main">
            {tournament.id && <div className="content">
                <div className="content text-center text-uppercase mb-4">
                    <h1>{i18n.language === LANGUAGE_ENGLISH ? tournament.titleEn : tournament.title}</h1>
                </div>
                <Suspense fallback={<LoadingComponent/>}>
                    <TournamentNavbarComponent tournament={tournament} active={'statistics'}/>
                </Suspense>
                <div className="main__content">
                    <Row>
                        <Col xl={9} lg={9} md={7} sm={12} xs={12}>
                            {tournament.stages?.length !== undefined &&
                            <Tab.Container id="left-tabs-example" defaultActiveKey="1">
                                <Row>
                                    <Col sm={3}>
                                        <Nav variant="pills" className="flex-column" defaultActiveKey={'tab_1'}>
                                            <h2>{i18n.t('label.tournament_stages')}</h2>
                                            <Nav.Item key={0}>
                                                <Nav.Link eventKey="tab_0">
                                                    {i18n.t('label.free_practice')}
                                                </Nav.Link>
                                            </Nav.Item>
                                            {tournament.stages?.map((stage) => (
                                                <Nav.Item key={stage.id}>
                                                    <Nav.Link eventKey={`tab_${stage.position}`}>
                                                        {i18n.language === LANGUAGE_ENGLISH ? stage.titleEn : stage.title}
                                                    </Nav.Link>
                                                </Nav.Item>
                                            ))}
                                        </Nav>
                                    </Col>
                                    <Col sm={9}>
                                        <Tab.Content>
                                            <Tab.Pane eventKey={`tab_0`} key={0}>
                                                <div className="content">
                                                    <Suspense fallback={<LoadingComponent/>}>
                                                        <PilotsRacingRankingComponent
                                                            title={i18n.t('label.free_practice')}/>
                                                    </Suspense>
                                                </div>
                                            </Tab.Pane>
                                            {tournament.stages?.map((stage) => (
                                                <Tab.Pane eventKey={`tab_${stage.position}`} key={stage.id}>
                                                    <div className="content">
                                                        <Suspense fallback={<LoadingComponent/>}>
                                                            <PilotsRacingRankingComponent
                                                                title={i18n.t('label.pilots_results')}
                                                                tournament={tournament} stage={stage}/>
                                                        </Suspense>
                                                    </div>
                                                </Tab.Pane>
                                            ))}
                                        </Tab.Content>
                                    </Col>
                                </Row>
                            </Tab.Container>}
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
    );
};

export default TournamentStatisticsPage;