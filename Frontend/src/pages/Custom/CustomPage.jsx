import React, {Suspense, useEffect} from "react";
import {URL_API_CUSTOM_PAGE} from "../../constants/urls";
import i18next from '../../i18n';
import ReactHtmlParser from 'react-html-parser';
import {LANGUAGE_ENGLISH} from "../../constants/languages";
import LoadingComponent from "../../components/Loading/LoadingComponent";

const FeedbackPage = (props) => {
    const url = props.match.params.url;
    const [page, setPage] = React.useState({});

    useEffect(() => {
        fetch(URL_API_CUSTOM_PAGE + `/${url}`)
            .then(r => r.json())
            .then(data => {
                if (data.status === 0) {
                    setPage(data.page);
                }
            })
            .catch((err) => {
                console.error(err);
            })
        ;
    }, [url]);

    return <main className="main">
        {page && <div className="content">
            <div className="main__content">
                <div className="text-center">
                    <h1>
                        {i18next.language === LANGUAGE_ENGLISH ? page.titleEn : page.titleRu}
                    </h1>
                </div>
                <div>
                    <Suspense fallback={<LoadingComponent/>}>
                        <div>
                            {ReactHtmlParser(i18next.language === LANGUAGE_ENGLISH ? page.contentEn : page.contentRu)}
                        </div>
                    </Suspense>
                </div>
            </div>

        </div>}
    </main>;
};

export default FeedbackPage;