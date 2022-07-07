import React, {useEffect} from 'react';
import i18next from "../../i18n";
import {URL_API_ARTICLES} from "../../constants/urls";
import history from "../../history";
import {LANGUAGE_ENGLISH, LANGUAGE_RUSSIAN} from "../../constants/languages";


const ArticlesComponent = () => {
    const [articles, setArticles] = React.useState([]);

    useEffect(() => {
        fetch(URL_API_ARTICLES + '/latest/6')
            .then(response => response.json())
            .then(response => setArticles(response))
        ;
    }, []);

    const handleClick = (article) => {
        history.push('/article/' + article.id, {server: article})
    };

    return (
        <div className="latest-news__list">
            {articles.map((article, i) => (
                <div key={article.id} className="news text-white pointer" onClick={() => {
                    handleClick(article)
                }}>
                    <img
                        src={article.image ? process.env.REACT_APP_API_HOST + '/uploads/images/articles/' + article.image : '/images/cover.jpg'}
                        alt={article.titleEn}/>
                    <div className="news__content">
                        <div className="news__title">
                            {i18next.language === LANGUAGE_ENGLISH && article.titleEn}
                            {i18next.language === LANGUAGE_RUSSIAN && article.title}
                        </div>
                        <a href="#" className="news__more button-yelow">{i18next.t('button.more')}</a>
                    </div>
                </div>
            ))}
        </div>
    );
}

export default ArticlesComponent;