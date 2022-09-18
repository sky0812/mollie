const path = require('path');

module.exports = {
	entry: './src/js/block.js',
	output: {
		path: __dirname,
		filename: 'assets/js/script.js',
	},
	module: {
		loaders: [
			{
				test: /.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/,
			},
		],
	},
};