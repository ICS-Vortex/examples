import React, {Suspense, useEffect} from 'react';
import i18next from "i18next";
import {NavLink} from "react-bootstrap";
import Moment from 'react-moment';
import {LANGUAGE_ENGLISH} from "../../../constants/languages";
import LoadingComponent from "../../../components/Loading/LoadingComponent";

const ServerVoiceComponent = React.lazy(() => import("../../../components/Server/Voice/ServerVoiceComponent"));
const VideosComponent = React.lazy(() => import("../../../components/Videos/VideosComponent"));
const ServerInfoComponent = React.lazy(() => import("../../../components/ServerInfo/ServerInfoComponent"));
const ServerHeaderComponent = React.lazy(() => import("../../../components/Navbar/ServerHeaderComponent"));
const ServerNavbarComponent = React.lazy(() => import("../../../components/Navbar/ServerNavbarComponent"));

const ServerHomePage = (props) => {
    const id = parseInt(props.match.params.id);
    const [articles, setArticles] = React.useState([]);
    const [server, setServer] = React.useState({});

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${id}`)
            .then(r => r.json())
            .then(data => {
                if (data.server) {
                    setServer(data.server);
                    fetch(process.env.REACT_APP_API_HOST + `/api/open/articles/get/${id}?page=${1}`)
                        .then(r => r.json())
                        .then(list => {
                            setArticles(list.articles);
                        })
                    ;
                }
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, []);

    const getArticleImage = (article) => {
        if (article.image) {
            return process.env.REACT_APP_API_HOST + '/uploads/images/articles/' + article.image;
        }
        return process.env.REACT_APP_API_HOST + '/images/cover.jpg';
    };

    return <>
        {server.id && <main className="main main_gradient">
            <div className="content">
                <Suspense fallback={<LoadingComponent/>}>
                    <ServerHeaderComponent server={server}/>
                </Suspense>
                <Suspense fallback={<LoadingComponent/>}>
                    <ServerNavbarComponent server={server} active={'home'}/>

                </Suspense>
                <div className="main__content">
                    <div className="two-col">
                        <div className="two-col__content">
                            <div className="title title_main">{i18next.t('label.server_news')}</div>
                            <div className="news-list">
                                {articles.map((article, i) => (
                                    <NavLink href={`/article/${article.id}`} className="news-item" key={i}>
                                        <span className="news-item__thumb">
                                            <img src={getArticleImage(article)}
                                                 alt={i18next.language === LANGUAGE_ENGLISH ? article.titleEn : article.title}/>
                                        </span>
                                        <span className="news-item__body">
                                            <span className="news-item__title">
                                                {i18next.language === LANGUAGE_ENGLISH ? article.titleEn : article.title}
                                            </span>
                                            <span className="news-item__desc">
                                                {i18next.language === LANGUAGE_ENGLISH ? article.descriptionEn : article.description}
                                            </span>
                                            <span className="news-item__date">
                                                <Moment format="DD.MM.YYYY, HH:MM">
                                                    {article.createdAt}
                                                </Moment>
                                                <i className="fa fa-eye ml-3 pointer"/><span
                                                className="ml-2">{article.views}</span>
                                            </span>
                                        </span>
                                    </NavLink>
                                ))}
                            </div>
                        </div>
                        <aside className="two-col__aside aside">
                            {server.isOnline && <Suspense fallback={<LoadingComponent/>}>
                                <ServerInfoComponent server={server}/>
                            </Suspense>}
                            <Suspense fallback={<LoadingComponent/>}>
                                <ServerVoiceComponent server={server}/>
                            </Suspense>
                            <Suspense fallback={<LoadingComponent/>}>
                                <VideosComponent server={server}/>
                            </Suspense>


                        </aside>
                    </div>
                </div>
            </div>
        </main>}
    </>;
};

export default ServerHomePage;
