import React, {useEffect} from 'react';

const ArticlePage = (props) => {
    const id = parseInt(props.match.params.id);
    const [article, setArticle] = React.useState({});
    const [found, setFound] = React.useState(false);

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/articles/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.article) {
                    setFound(true);
                    setArticle(data.article);
                }
            })
            .catch(error => {
                // console.log(error);
            })
        ;
    }, []);

    return <React.Fragment>
        {/*<Container fixed>*/}
        {/*    {found && <Paper elevation={3} className="m-10 p-10">*/}
        {/*        <Typography variant="h2" component="h2" gutterBottom>*/}
        {/*            {i18next.language === 'en' ? article.titleEn : article.title}*/}
        {/*        </Typography>*/}
        {/*        <Typography variant="body1" component="div" gutterBottom>*/}
        {/*            {ReactHtmlParser((i18next.language === 'en' ? article.en : article.ru))}*/}
        {/*        </Typography>*/}
        {/*    </Paper>}*/}
        {/*</Container>*/}
    </React.Fragment>;
}

export default ArticlePage;