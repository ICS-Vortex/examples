import React, {useEffect} from 'react';
import ServerHeaderComponent from "../../../components/Navbar/ServerHeaderComponent";
import ServerNavbarComponent from "../../../components/Navbar/ServerNavbarComponent";
import i18next from "i18next";
import {LANGUAGE_ENGLISH, LANGUAGE_RUSSIAN} from "../../../constants/languages";
import ReactHtmlParser from "react-html-parser";

const ServerInfoPage = (props) => {
    const id = parseInt(props.match.params.id);
    const [server, setServer] = React.useState({});

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${id}`)
            .then(r => r.json())
            .then(data => {
                if (data.server) {
                    setServer(data.server);
                }
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, []);

    return (
        <React.Fragment>
            {server.id && <main className="main main_gradient">
                <div className="content">
                    <ServerHeaderComponent server={server}/>
                    <ServerNavbarComponent server={server} active={'info'}/>
                    <div className="main__content">
                        <div className="title title_main">{server.name}</div>
                        <div className="news-list">
                            <div className="stats__block">
                                <div className="graph">
                                    {i18next.language === LANGUAGE_RUSSIAN && ReactHtmlParser(server.description)}
                                    {i18next.language === LANGUAGE_ENGLISH && ReactHtmlParser(server.descriptionEn)}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </main>}
        </React.Fragment>
    );
}

export default ServerInfoPage;