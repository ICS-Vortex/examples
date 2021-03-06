import React, {useEffect} from 'react';
import {URL_API_SERVERS} from "../../constants/urls";

const TopSortiesFightersComponent = ({tour, server}) => {
    const [topList, setTopList] = React.useState([]);
    useEffect(() => {
        if (server) {
            fetch(URL_API_SERVERS + `/${server.id}/top-sorties-fighters?tour=${tour}`)
                .then(r => r.json())
                .then(data => setTopList(data));
        }
    }, [server, tour]);

    return (
        <React.Fragment>
            {/*<PanelComponent>{i18next.t('label.top_sorties_fighters')}</PanelComponent>*/}
            {/*<TableContainer component={Paper}>*/}
            {/*    <Table aria-label="elo table">*/}
            {/*        <TableHead>*/}
            {/*            <TableRow>*/}
            {/*                <TableCell>{i18next.t('label.callsign')}</TableCell>*/}
            {/*                <TableCell>{i18next.t('label.rating')}</TableCell>*/}
            {/*            </TableRow>*/}

            {/*        </TableHead>*/}
            {/*        <TableBody>*/}
            {/*            {topList.map((row: TopSortieFighter, i) => (*/}
            {/*                <TableRow key={i}>*/}
            {/*                    <TableCell>*/}
            {/*                        <Link href={'#'}>{row.callsign}</Link>*/}
            {/*                    </TableCell>*/}
            {/*                    <TableCell>{row.ks}</TableCell>*/}
            {/*                </TableRow>*/}
            {/*            ))}*/}
            {/*        </TableBody>*/}
            {/*    </Table>*/}
            {/*</TableContainer>*/}
        </React.Fragment>
    );
}

export default TopSortiesFightersComponent;