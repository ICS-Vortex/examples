import React, {Suspense, useEffect} from 'react';
import i18next from "i18next";
import {Card} from "react-bootstrap";
import {LANGUAGE_ENGLISH, LANGUAGE_RUSSIAN} from "../../../constants/languages";
import ReactHtmlParser from "react-html-parser";
import LoadingComponent from "../../../components/Loading/LoadingComponent";

const ServerHeaderComponent = React.lazy(() => import('../../../components/Navbar/ServerHeaderComponent'));
const ServerNavbarComponent = React.lazy(() => import('../../../components/Navbar/ServerNavbarComponent'));

const ServerFaqPage = (props) => {
    const id = parseInt(props.match.params.id);

    const [server, setServer] = React.useState({});
    const [faqs, setFaqs] = React.useState([]);

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${id}`)
            .then(r => r.json())
            .then(data => {
                // console.log(data.server);
                if (data.server) {
                    setServer(data.server);
                    fetch(process.env.REACT_APP_API_HOST + `/api/open/faq/server/${data.server.id}`)
                        .then(response => response.json())
                        .then(data => {
                            setFaqs(data);
                        })
                    ;
                }
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, []);

    return (
        <React.Fragment>
            {server.id && <main className="main main_gradient text-white">
                <div className="content">
                    <Suspense fallback={<LoadingComponent/>}>
                        <ServerHeaderComponent server={server}/>
                    </Suspense>
                    <Suspense fallback={<LoadingComponent/>}>
                        <ServerNavbarComponent server={server} active={'faq'}/>
                    </Suspense>

                    <div className="main__content">
                        {faqs.map((faq, i) => (
                            <Card className="mb-3" style={{background: '#1F1F2C'}}>
                                <Card.Body>
                                    <Card.Title as="h2">
                                        {i18next.language === LANGUAGE_ENGLISH && faq.questionEn}
                                        {i18next.language === LANGUAGE_RUSSIAN && faq.question}
                                    </Card.Title>
                                    <Card.Text>
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

export default ServerFaqPage;