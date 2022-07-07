import React, {useEffect} from 'react';
import i18next from "i18next";
import {Card} from "react-bootstrap";
import {LANGUAGE_ENGLISH, LANGUAGE_RUSSIAN} from "../../../constants/languages";
import ReactHtmlParser from "react-html-parser";
import TournamentNavbarComponent from "../../../components/Tournament/Navbar/TournamentNavbarComponent";
import ServerHeaderComponent from "../../../components/Navbar/ServerHeaderComponent";
import i18n from "../../../i18n";

const TournamentFaqPage = (props) => {
    const [tournament, setTournament] = React.useState({});

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/tournaments/${parseInt(props.match.params.id)}/info`)
            .then(r => r.json())
            .then(data => {
                setTournament(data);
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, []);

    return (
        <React.Fragment>
            {tournament.id && <main className="main text-white">
                <div className="content text-center text-uppercase mb-4">
                    <h1>{i18n.language === LANGUAGE_ENGLISH ? tournament.titleEn : tournament.title}</h1>
                </div>
                <div className="content">
                    {tournament?.servers?.map((server) => (
                        <ServerHeaderComponent key={server.id} server={server}/>
                    ))}
                    <TournamentNavbarComponent tournament={tournament} active={'faq'}/>
                    <div className="main__content">
                        {tournament?.faqs?.map((faq, i) => (
                            <Card key={faq.id} className="mb-3" style={{background: '#1F1F2C'}}>
                                <Card.Body>
                                    <Card.Title as="h2">
                                        {i18next.language === LANGUAGE_ENGLISH && faq.questionEn}
                                        {i18next.language === LANGUAGE_RUSSIAN && faq.question}
                                    </Card.Title>
                                    <Card.Text as="div">
                                        {i18next.language === LANGUAGE_ENGLISH && ReactHtmlParser(faq.answerEn)}
                                        {i18next.language === LANGUAGE_RUSSIAN && ReactHtmlParser(faq.answer)}
                                    </Card.Text>
                                </Card.Body>
                            </Card>
                        ))}
                    </div>
                </div>

            </main>}
        </React.Fragment>
    );
}

export default TournamentFaqPage;