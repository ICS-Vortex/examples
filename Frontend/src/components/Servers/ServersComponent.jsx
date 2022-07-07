import React, {Suspense, useEffect} from 'react';
import {URL_API_SERVERS} from "../../constants/urls";
import {Col, Container, Row} from "react-bootstrap";
import LoadingComponent from "../Loading/LoadingComponent";

const ServerComponent = React.lazy(() => import('../Server/ServerComponent'));

const ServersComponent = () => {
    const [servers, setServers] = React.useState([]);

    useEffect(() => {
        fetch(URL_API_SERVERS + '/list/all')
            .then(response => response.json())
            .then(response => {
                setServers(response);
            })
        ;
    }, []);

    const getServerLink = (server) => {
        if (server.inTournament) {
            return `/racing`;
        }
        return `/server/${server.id}/stats`;
    };

    return <Container fluid>
        <Row>
            {servers.map((server, i) => (
                <Col xl={3} lg={4} md={6} sm={12} xs={12} key={server.id} className="mb-5">
                    <Suspense fallback={<LoadingComponent/>}>
                        <ServerComponent server={server}/>
                    </Suspense>
                </Col>
            ))}
        </Row>
    </Container>;
}

export default ServersComponent;