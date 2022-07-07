import React, {Suspense, useEffect} from 'react';
import i18next from "i18next";
import {NavLink} from "react-bootstrap";
import Moment from 'react-moment';
import {PAGE_HOME} from "../../../constants/routes";
import {FacebookIcon, FacebookShareButton} from "react-share";
import {useLocation} from 'react-router-dom'
import {LANGUAGE_ENGLISH} from "../../../constants/languages";
import ReactHtmlParser from "react-html-parser";
import LoadingComponent from "../../../components/Loading/LoadingComponent";

const ServerHeaderComponent = React.lazy(() => import('../../../components/Navbar/ServerHeaderComponent'));
const ServerInfoComponent = React.lazy(() => import('../../../components/ServerInfo/ServerInfoComponent'));
const ServerVoiceComponent = React.lazy(() => import('../../../components/Server/Voice/ServerVoiceComponent'));
const VideosComponent = React.lazy(() => import('../../../components/Videos/VideosComponent'));

const ServerArticlePage = (props) => {
    const id = parseInt(props.match.params.id);
    const [server, setServer] = React.useState({});
    const [article, setArticle] = React.useState({});
    const location = useLocation();

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/articles/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.article) {
                    setArticle(data.article);
                }
                if (data.server) {
                    setServer(data.server);
                }
            })
            .catch(error => {
                //
            })
        ;
    }, []);

    console.log(process.env.REACT_APP_HOST + location.pathname)
    return (
        <React.Fragment>
            <main className="main main_gradient">
                <div className="content">
                    {server.id && <Suspense fallback={<LoadingComponent/>}>
                        <ServerHeaderComponent server={server}/>
                    </Suspense>}
                    <div className="main__content">
                        {!server.id && <div>
                            <NavLink href={PAGE_HOME}>
                                <i className="fa fa-arrow-circle-left mr-3 pointer"/>{i18next.t('page.home')}
                            </NavLink>
                        </div>}
                        {server.id && <div>
                            <NavLink href={`/server/${server.id}/home`}>
                                <i className="fa fa-arrow-circle-left mr-3 pointer"/>{server.name}
                            </NavLink>
                        </div>}
                        {article.id && <span className="text-muted">
                            <Moment format="DD.MM.YYYY">
                                {article.createdAt}
                            </Moment>
                            <i className="fa fa-eye ml-3 pointer"/><span className="ml-2">{article.views}</span>
                            <FacebookShareButton
                                url={(process.env.REACT_APP_HOST + location.pathname)}
                                quote={(i18next.language === LANGUAGE_ENGLISH ? article.description : article.descriptionEn)}
                                className="ml-3"
                                title={i18next.t('label.share')}
                            >
                                <FacebookIcon size={32} round/>
                            </FacebookShareButton>
                        </span>}

                        <div className="two-col">
                            <div className="two-col__content">
                                <div className="title title_main">
                                    {i18next.language === LANGUAGE_ENGLISH ? article.titleEn : article.title}
                                </div>
                                <div className="news-list">
                                    {ReactHtmlParser((i18next.language === LANGUAGE_ENGLISH ? article.en : article.ru))}
                                </div>
                            </div>
                            {server.id && <aside className="two-col__aside aside">
                                <Suspense fallback={<LoadingComponent/>}>
                                    <ServerInfoComponent server={server}/>
                                </Suspense>
                                <Suspense fallback={<LoadingComponent/>}>
                                    <ServerVoiceComponent server={server}/>
                                </Suspense>
                                <Suspense fallback={<LoadingComponent/>}>
                                    <VideosComponent server={server}/>
                                </Suspense>
                            </aside>}
                        </div>
                    </div>
                </div>
            </main>
        </React.Fragment>
    );
}

export default ServerArticlePage;