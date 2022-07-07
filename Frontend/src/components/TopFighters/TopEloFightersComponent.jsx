import React, {useEffect} from 'react';
import {URL_API_SERVERS} from "../../constants/urls";
import i18next from "i18next";

const TopEloFightersComponent = ({tour, server}) => {
    const [topList, setTopList] = React.useState([]);
    useEffect(() => {
        if (server) {
            fetch(URL_API_SERVERS + `/${server.id}/top-elo-fighters?tour=${tour}`)
                .then(r => r.json())
                .then(data => setTopList(data));
        }
    }, [server, tour]);

    return (
        <React.Fragment>
            <div>{i18next.t('label.top_elo_fighters')}</div>
            {/*<TableContainer component={Paper}>*/}
            {/*    <Table aria-label="elo table">*/}
            {/*        <TableHead>*/}
            {/*            <TableRow>*/}
            {/*                <TableCell>{i18next.t('label.callsign')}</TableCell>*/}
            {/*                <TableCell>{i18next.t('label.rating')}</TableCell>*/}
            {/*            </TableRow>*/}

            {/*        </TableHead>*/}
            {/*        <TableBody>*/}
            {/*            {topList.map((row: TopEloFighter, i) => (*/}
            {/*                <TableRow key={i}>*/}
            {/*                    <TableCell>*/}
            {/*                        <Link href={'#'}>{row.callsign}</Link>*/}
            {/*                    </TableCell>*/}
            {/*                    <TableCell>{row.rating}</TableCell>*/}
            {/*                </TableRow>*/}
            {/*            ))}*/}
            {/*        </TableBody>*/}
            {/*    </Table>*/}
            {/*</TableContainer>*/}
        </React.Fragment>
    );
}

export default TopEloFightersComponent;