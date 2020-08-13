// Import vendor dependencies
const { __ } = wp.i18n;

// Import styles
import './style.scss';

// Import utilities
import { genereateFormPreviewWithOnboardingAPI } from '../../../utils';

import Card from '../../../components/card';
import GiveLogo from '../../../components/give-logo';
import ContinueButton from '../../../components/continue-button';
import DismissLink from '../../../components/dismiss-link';

const Introduction = () => {
	const onStartSetup = () => {
		genereateFormPreviewWithOnboardingAPI();
	};

	return (
		<div className="give-obw-introduction">
			<Card>
				<div className="give-obw-introduction__content">
					<h1 className="give-obw-introduction__heading">
						{ __( 'Welcome To', 'give' ) }<span className="screen-reader-text">{ __( 'GiveWP', 'give' ) }</span>
					</h1>
					<GiveLogo />
					<p>
						{ __( 'You’re only minutes away from having a fully functional online donation platform on your website. We recommend using the setup wizard if this is your first time using Give.', 'give' ) }
					</p>
					<ContinueButton clickCallback={ onStartSetup } label={ __( 'Start Setup', 'give' ) } />
				</div>
			</Card>
			<DismissLink />
		</div>
	);
};

export default Introduction;
