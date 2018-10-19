function tree2array(tree, child, deep = 0) {
	if (Array.isArray(tree) === false)
		return [];

	let res = [];
	for (let item of tree) {
		item.deep = deep;
		if (item.hasOwnProperty(child) && Array.isArray(item[child])) {
			item.isFolder = true;
			res.push(item);
			res = [...res, ...tree2array(item[child], child, deep + 1)];
		} else {
			res.push(item);
		}
	}
	return res;
}

export default function (tree, child = 'children') {
	return tree2array(tree, child);
}
