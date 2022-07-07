import React, {Suspense, useEffect} from "react";
import i18next from "../../../i18n";
import i18n from "../../../i18n";
import ReactHtmlParser from "react-html-parser";
import {LANGUAGE_ENGLISH} from "../../../constants/languages";
import LoadingComponent from "../../../components/Loading/LoadingComponent";
import {Col, Row} from "react-bootstrap";

const TournamentNavbarComponent = React.lazy(() => import('../../../components/Tournament/Navbar/TournamentNavbarComponent'));
const ServerComponent = React.lazy(() => import('../../../components/Server/ServerComponent'));

const TournamentCustomPage = (props) => {
    const url = props.match.params.url;
    const id = parseInt(props.match.params.id);
    const [page, setPage] = React.useState({});
    const [tournament, setTournament] = React.useState({});

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/tournaments/${id}/info`)
            .then(r => r.json())
            .then((tourn) => {
                setTournament(tourn);
                tourn.customPages?.map(p => {
                    if (p.url === url) {
                        setPage(p);
                    }
                });
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, [id, url]);


    return <main className="main">
        {page.id && <div className="content">
            <div className="content text-center text-uppercase mb-4">
                <h1>{i18n.language === LANGUAGE_ENGLISH ? tournament.titleEn : tournament.title}</h1>
            </div>
            <Suspense fallback={<LoadingComponent/>}>
                <TournamentNavbarComponent tournament={tournament} active={page.url}/>
            </Suspense>
            <div className="main__content">
                <Row>
                    <Col>
                        <div className="text-center">
                            <h1>
                                {i18next.language === LANGUAGE_ENGLISH ? page.titleEn : page.titleRu}
                            </h1>
                        </div>
                    </Col>
                </Row>
                <Row>
                    <Col xl={9} lg={9} md={7} sm={12} xs={12}>
                        <div>
                            <div>
                                {ReactHtmlParser(i18next.language === LANGUAGE_ENGLISH ? page.contentEn : page.contentRu)}
                            </div>
                        </div>
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
    </main>;
};

export default TournamentCustomPage;