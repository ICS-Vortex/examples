import React from 'react';
import {NavLink} from "react-bootstrap";
import i18next from "../../../i18n";
import {LANGUAGE_ENGLISH} from "../../../constants/languages";

const ServerArticleComponent = ({article, id}) => {
    return (
        <NavLink href={`/article/${article.id}`} className="news-item" key={id}>
            <span className="news-item__thumb">
                <img
                    src={article.image ? process.env.REACT_APP_API_HOST + '/uploads/images/articles/' + article.image : '/images/cover.jpg'}
                    alt={i18next.language === LANGUAGE_ENGLISH ? article.titleEn : article.title}/>
            </span>
            <span className="news-item__body">
                <span
                    className="news-item__title">{i18next.language === LANGUAGE_ENGLISH ? article.titleEn : article.title}</span>
                <span
                    className="news-item__desc">{i18next.language === LANGUAGE_ENGLISH ? article.descriptionEn : article.description}</span>
                <span className="news-item__date">{article.createdAt}</span>
            </span>
        </NavLink>
    );
}

export default ServerArticleComponent;