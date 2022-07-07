import React, {Suspense, useEffect} from "react";
import {Col, Row} from "react-bootstrap";
import i18n from "../../../i18n";
import {LANGUAGE_ENGLISH} from "../../../constants/languages";
import ReactHtmlParser from "react-html-parser";
import LoadingComponent from "../../../components/Loading/LoadingComponent";

const ServerHeaderComponent = React.lazy(() => import("../../../components/Navbar/ServerHeaderComponent"))
const ServerComponent = React.lazy(() => import("../../../components/Server/ServerComponent"))
const TournamentNavbarComponent = React.lazy(() => import("../../../components/Tournament/Navbar/TournamentNavbarComponent"))

const TournamentHomePage = (props) => {
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
    }, []);

    return <React.Fragment>
        <Suspense fallback={<LoadingComponent/>}>
            <main className="main">
                <div className="content text-center text-uppercase">
                    <h1>{i18n.language === LANGUAGE_ENGLISH ? tournament.titleEn : tournament.title}</h1>
                </div>

                {tournament.id && <div className="content">
                    <TournamentNavbarComponent tournament={tournament} active={'home'}/>
                    <div className="main__content">
                        <Row>
                            <Col xl={9} lg={9} md={7} sm={12} xs={12}>
                                {ReactHtmlParser(i18n.language === LANGUAGE_ENGLISH ? tournament.descriptionEn : tournament.description)}
                            </Col>
                            <Col xl={3} lg={3} md={5} sm={12} xs={12}>
                                {tournament?.servers?.map((server) => (
                                    <ServerComponent key={server.id} server={server}/>
                                ))}
                            </Col>
                        </Row>
                    </div>
                </div>}
            </main>
        </Suspense>
    </React.Fragment>;
}
export default TournamentHomePage;
